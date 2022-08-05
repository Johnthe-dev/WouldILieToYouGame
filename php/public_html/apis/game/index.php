<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/Classes/autoload.php";
require_once("/etc/apache2/game/Secrets.php");
require_once dirname(__DIR__, 3) . "/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/lib/jwt.php";
require_once dirname(__DIR__, 3) . "/lib/uuid.php";

use JOHNTHEDEV\Game\{Game, Statement, Player, Vote};

/**
 * api for getting the game state, creating a game, etc.
 *
 * @author John Johnson-Rodgers <john@johnthe.dev>
 */

//verify the session, start if inactive
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
    //grab the mySQL connection
    $secrets = new \Secrets("var/www/apache/secret/game.ini");
    $pdo = $secrets->getPdoObject();

    //determine which HTTP method was used
    $method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

    //sanitize input
    $gameId = filter_input(INPUT_GET, "gameId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $gameGetCurrentState = filter_input(INPUT_GET, "getCurrentState", FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

    //check if resourceId is empty and method is delete or put
    if(($method === "DELETE" || $method === "PUT") && (empty($gameId) === true)) {
        throw(new InvalidArgumentException("gameId can not be empty when deleting or changing", 400));
    }

    if($method === "GET") {
        //set xsrf cookie
        setXsrfCookie();

        if(isset($gameId) === true && isset($gameGetCurrentState) === true && $gameGetCurrentState === true) {
            //get game by game id
            $game = Game::getGameByGameId($pdo, $gameId);
            if($game!=null){
                $currentPlayer = Player::getPlayerByPlayerId($pdo, $game->getGameCurrentPlayerId());
                $statement = Statement::getStatementByStatementId($pdo, $game->getGameCurrentStatementId());
                $statementToSend = new Statement($statement->getStatementId(), $statement->getStatementText(),null,$statement->getStatementUsed(), $statement->getStatementPlayerId());
                $votes = Vote::getVotesByStatementId($pdo, $statement->getStatementId());
                $players = Player::getPlayersByGameId($pdo, $gameId);
                $reply->data = (object) array('Game'=>$game, "CurrentStatement"=>$statementToSend, 'CurrentVotes'=>$votes, 'CurrentPlayer'=>$currentPlayer, 'Players'=>$players);
            } else{
                $reply->data = null;
            }

        } elseif (isset($gameId) === true){
            $game = Game::getGameByGameId($pdo, $gameId);
            $reply->data = $game;
        }

    } elseif($method === "POST" || $method === "PUT") {
        //enforce xsrf token
        verifyXsrf();
        if(empty($_SESSION["player"]) === true) {
            throw(new \InvalidArgumentException("You do not have permission to edit or add game", 401));
        }

        //retrieves JSON package that was sent by the user and stores it in $requestContent using file_get_contents
        $requestContent = file_get_contents("php://input");

        //Decodes content and stores result in $requestContent
        $requestObject = json_decode($requestContent);

        if($method === "POST") {
            //makes sure required fields are available
            if (!property_exists($requestObject, "gameCode")) {
                throw(new \InvalidArgumentException("The Game Code field is empty.", 400));
            }
            $game = Game::getGameByGameCode($pdo, $requestObject->gameCode);
            if($game = null) {
                //create new game and insert it into the database
                $game = new Game(generateUuidV4(), $requestObject->gameCode, null, null,
                    null, null, 0, 0, 0, 1);
                $game->insert($pdo);
            }
            $_SESSION["player"]->setPlayerGameId($game->getGameId());
            Player::assignPlayerToTeam($pdo, $_SESSION["player"]);
            $_SESSION["player"]->setPlayerTeamNumber(1);
            $_SESSION["player"]->update($pdo);
            //update reply
            $reply->data = $game;
            $reply->message = "A new game entry has been created.";
        } elseif ($method === "PUT") {
            $game = Game::getGameByGameId($pdo, $gameId);
            if($game === null) {
                throw (new RuntimeException("Game to be updated does not exist", 404));
            }

            if (!property_exists($requestObject, "gameCode")) {
                $requestObject["gameCode"]=$game->getGameCode();
            }

            if (!property_exists($requestObject, "gameCreated")) {
                $requestObject["gameCreated"]=$game->getGameCreated();
            }

            $requestObject["gameActivity"] = null;

            if (!property_exists($requestObject, "gameCurrentPlayerId")) {
                $requestObject["gameCurrentPlayerId"]=$game->getGameCurrentPlayerId();
            }

            if (!property_exists($requestObject, "gameCurrentStatementId")) {
                $requestObject["gameCurrentStatementId"]=$game->getGameCurrentStatementId();
            }

            $currentStateChanged = false;
            if (!property_exists($requestObject, "gameCurrentState")) {
                $requestObject["gameCurrentState"]=$game->getGameCurrentState();
            } elseif($requestObject["gameCurrentState"]!==$game->getGameCurrentState()){
                $currentStateChanged = true;
            } elseif ($game->getGameCurrentState()===5){
                $currentStateChanged = true;
            }
            if (!property_exists($requestObject, "gameTeamOneScore")) {
                $requestObject["gameTeamOneScore"]=$game->getGameTeamOneScore();
            }

            if (!property_exists($requestObject, "gameTeamTwoScore")) {
                $requestObject["gameTeamTwoScore"]=$game->getGameTeamTwoScore();
            }
            $requestObject["gameRound"]  = $game->getGameRound();

            $game = new Game(
                $gameId,
                $requestObject["gameCode"],
                $requestObject["gameCreated"],
                $requestObject["gameActivity"],
                $requestObject["gameCurrentPlayerId"],
                $requestObject["gameCurrentStatementId"],
                $requestObject["gameCurrentState"],
                $requestObject["gameTeamOneScore"],
                $requestObject["gameTeamTwoScore"],
                $requestObject["gameRound"]
            );
            $game->update($pdo);

            /**
             * Game States
             * 0 Not Yet Started
             * 1 In Progress
             * 2 Calculate Score
             * 3 Getting New Question
             * 4 New Round
             * 5 Game Over
             */
            if($currentStateChanged) {
                switch ($game->getGameCurrentState()) {
                    case 0:
                    case 1:
                        break;
                    case 2:
                        $currentStatementId = $game->getGameCurrentStatementId()->toString();
                        if ($currentStatementId === null) {
                            throw(new \InvalidArgumentException("Cannot vote without active statement"));
                        }
                        $currentPlayer = Player::getPlayerByPlayerId($pdo, $game->getGameCurrentPlayerId()->toString());
                        $correct = Vote::getVoteResultsByStatementId($pdo, $currentStatementId);
                        if ($correct) {
                            if ($currentPlayer->getPlayerTeamNumber() == 1) {
                                $newScore = $game->getGameTeamTwoScore() + 1;
                                $game->setGameTeamTwoScore($newScore);
                            } else {
                                $newScore = $game->getGameTeamOneScore() + 1;
                                $game->setGameTeamOneScore($newScore);
                            }
                        } else {
                            if ($currentPlayer->getPlayerTeamNumber() == 1) {
                                $newScore = $game->getGameTeamOneScore() + 1;
                                $game->setGameTeamOneScore($newScore);
                            } else {
                                $newScore = $game->getGameTeamTwoScore() + 1;
                                $game->setGameTeamTwoScore($newScore);
                            }
                        }
                        $game->setGameCurrentState(1);
                        $game->update($pdo);
                        break;
                    case 3:
                        if ($game->getGameCurrentStatementId() === null) {
                            $formerPlayerId = $game->getGameCurrentPlayerId()->toString();
                            if ($formerPlayerId === null) {
                                $newCurrentPlayer = Player::getRandomPlayerByGameId($pdo, $gameId, 1);
                            } else {
                                $formerPlayer = Player::getPlayerByPlayerId($pdo, $formerPlayerId);
                                $teamNumber = $formerPlayer->getPlayerTeamNumber() == 1 ? 2 : 1;
                                $newCurrentPlayer = Player::getRandomPlayerByGameId($pdo, $gameId, $teamNumber);
                            }
                            if($newCurrentPlayer === null){
                                $game->setGameCurrentState(4);
                            } elseif ($game->getGameCurrentState()!==5){
                                $newCurrentStatement = Statement::getNextStatement($pdo, $newCurrentPlayer);
                                $game->setGameCurrentState(1);
                            }
                            $game->update($pdo);
                            $reply->data = $game;
                        }
                        break;
                    case 4:
                        $newGameRound = $game->getGameRound() +1;
                        if( $newGameRound <=3){
                            $allPlayers = Player::getPlayersByGameId($pdo, $gameId);
                            foreach ($allPlayers as $player){
                                $player->setPlayerPlayed = false;
                                $player->update($pdo);
                            }
                            $newCurrentPlayer = Player::getRandomPlayerByGameId($pdo, $gameId, 1);
                            $game->setGameRound($newGameRound);
                            $newCurrentStatement = Statement::getNextStatement($pdo, $newCurrentPlayer);
                            $game->setGameCurrentState(1);
                        } else{
                            $game->setGameCurrentState(5);
                        }
                        $game->update($pdo);
                        break;
                    case 5:
                        $game->delete($pdo);
                        break;
                }
            }
        }
    } elseif ($method === "DELETE" ) {
        $game = Game::getGameByGameId($pdo, $gameId);
        //make sure it exists
        if($game === null) {
            throw (new RuntimeException("Game to be deleted does not exist", 404));
        }
        $game->delete($pdo);
        $reply->message = "Game deleted";
    } else {
        throw (new InvalidArgumentException("Invalid HTTP method request", 405));
    }

} catch(\Exception | \TypeError $exception) {
    $reply->status = $exception->getCode();
    $reply->message = $exception->getMessage();
}
header("Content-type: application/json");
echo json_encode($reply);