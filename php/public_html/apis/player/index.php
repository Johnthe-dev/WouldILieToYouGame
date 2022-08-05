<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/Classes/autoload.php";
require_once("/etc/apache2/game/Secrets.php");
require_once dirname(__DIR__, 3) . "/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/lib/jwt.php";
require_once dirname(__DIR__, 3) . "/lib/uuid.php";

use JOHNTHEDEV\Game\{Game, Statement, Player, Vote};

/**
 * api for getting the player, creating a player, etc.
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
    $playerId = filter_input(INPUT_GET, "playerId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $assignToTeam = filter_input(INPUT_GET, "assignToTeam", FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    $gameCode = filter_input(INPUT_GET, "gameCode", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    //check if resourceId is empty and method is delete or put
    if (($method === "DELETE" || $method === "PUT") && (empty($playerId) === true)) {
        throw(new InvalidArgumentException("playerId can not be empty when deleting or changing", 400));
    }

    if ($method === "GET") {
        //set xsrf cookie
        setXsrfCookie();

        if (isset($playerId) === true) {
            //get player by player id
            $player = Player::getPlayerByPlayerId($pdo, $playerId);
            $reply->data = $player;
        }

    } elseif ($method === "PUT") {
        //enforce xsrf token
        verifyXsrf();
        if (empty($_SESSION["player"]) === true) {
            throw(new \InvalidArgumentException("You do not have permission to edit or add player", 401));
        }
        //retrieves JSON package that was sent by the user and stores it in $requestContent using file_get_contents
        $requestContent = file_get_contents("php://input");

        //Decodes content and stores result in $requestContent
        $requestObject = json_decode($requestContent);


        $player = Player::getPlayerByPlayerId($pdo, $playerId);

        if ($player === null) {
            throw (new RuntimeException("Player to be updated does not exist", 404));
        }

        if($gameCode){
            $game = Game::getGameByGameCode($pdo, $gameCode);
            $requestObject["playerGameId"]=$game->getGameId();
        }

        if($assignToTeam===true){
            $player = Player::assignPlayerToTeam($pdo, $player);
        }
        $requestObject["playerTeamNumber"] = $player->getPlayerTeamNumber();

        if (!property_exists($requestObject, "playerGameId")) {
            $requestObject["playerGameId"] = $player->getPlayerGameId();
        }

        if (!property_exists($requestObject, "playerName")) {
            $requestObject["playerName"] = $player->getPlayerName();
        }


        if (!property_exists($requestObject, "playerPlayed")) {
            $requestObject["playerPlayed"] = $player->getPlayerPlayed();
        }

        $requestObject["playerLastModified"] = null;

        $player = new Player(
            $playerId,
            $requestObject["playerGameId"],
            $requestObject["playerName"],
            $requestObject["playerTeamNumber"],
            $requestObject["playerPlayed"],
            $requestObject["playerLastModified"],
        );
        $player->update($pdo);

    } elseif ($method === "DELETE" ) {
        $player = Player::getPlayerByPlayerId($pdo, $playerId);
        //make sure it exists
        if($player === null) {
            throw (new RuntimeException("Player to be deleted does not exist", 404));
        }
        $player->delete($pdo);
        $reply->message = "Player deleted";
    } else {
        throw (new InvalidArgumentException("Invalid HTTP method request", 405));
    }

} catch(\Exception | \TypeError $exception) {
    $reply->status = $exception->getCode();
    $reply->message = $exception->getMessage();
}
header("Content-type: application/json");
echo json_encode($reply);