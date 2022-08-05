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
        throw(new InvalidArgumentException("gameId can not be empty when deleting of changing", 400));
    }

    if($method === "GET") {
        //set xsrf cookie
        setXsrfCookie();

        if(isset($gameId) === true && isset($gameGetCurrentState)===true && $gameGetCurrentState === true) {
            //get game by game id
            $game = Game::getGameByGameId($pdo, $gameId);
            if($game!=null){
                $currentPlayer = Player::getPlayerByPlayerId($pdo, $game->getGameCurrentPlayerId());
                $statement = Statement::getStatementByStatementId($pdo, $game->getGameCurrentStatementId());
                $statementToSend = new Statement($statement->getStatementId(), $statement->getStatementText(),null,$statement->getStatementUsed(), $statement->getStatementPlayerId());
                $votes = Vote::getVotesByStatementId($pdo, $statement->getStatementId());
                $reply->data = (object) array('Game'=>$game, "CurrentStatement"=>$statementToSend, 'CurrentVotes'=>$votes, 'CurrentPlayer'=>$currentPlayer);
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

        if(!property_exists($requestObject, "gameCode")){$requestObject["gameCode"]=NULL;}

        if($method === "POST") {
            //makes sure required fields are available
            if(empty($requestObject->gameCode) === true) {
                throw(new \InvalidArgumentException("The Game Code field is empty.", 400));
            }

            //create new game and insert it into the database
            $game = new Game(generateUuidV4(), $requestObject->gameCode,null,null,
                null,null,0,0,0);
            $game->insert($pdo);
            //update reply
            $reply->data = $game;
            $reply->message = "A new game entry has been created.";
        }
    } else {
        throw (new InvalidArgumentException("Invalid HTTP method request", 405));
    }

} catch(\Exception | \TypeError $exception) {
    $reply->status = $exception->getCode();
    $reply->message = $exception->getMessage();
}
header("Content-type: application/json");
echo json_encode($reply);