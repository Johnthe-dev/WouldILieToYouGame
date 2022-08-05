<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/Classes/autoload.php";
require_once("/etc/apache2/game/Secrets.php");
require_once dirname(__DIR__, 3) . "/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/lib/uuid.php";

use JOHNTHEDEV\Game\{Player};

/**
 * api for signing-up as a player
 *
 * @author John Johnson-Rodgers <john@johnthe.dev>
 */

//verify session
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
    //connect to mySQL
    $secrets = new \Secrets("/var/www/apache/secret/game.ini");
    $pdo = $secrets->getPdoObject();

    //determine what http method was used
    $method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

    if($method === "POST") {
        if(isset($_SESSION['player'])){
            unset($_SESSION["player"]);
        }
        //decode the json and turn it into a php object
        $requestContent = file_get_contents("php://input");
        $requestObject = json_decode($requestContent);
        if(empty($requestObject->name)){
            throw(new \InvalidArgumentException("Please provide your player name", 401));
        }
        if($requestObject->teamNumber == null){
            $requestObject->teamNumber = 1;
        }
        //generate player attributes
        $playerId = generateUuidV4();
        $playerGameId = null;
        $playerName = $requestObject->name;
        $playerTeamNumber = $requestObject->teamNumber;
        $playerPlayed = false;
        $playerLastModified = null;

        //create the player object and prepare to insert it into the database
        $player = new Player(
            $playerId,
            $playerGameId,
            $playerName,
            $playerTeamNumber,
            $playerPlayed,
            $playerLastModified);

        //insert player into database
        $player->insert($pdo);
        $_SESSION["player"] = $player;

        //update reply
        $reply->message = "You have successfully become a player!";
    } else {
        throw(new InvalidArgumentException("Invalid http request", 405));
    }

} catch(\Exception | \TypeError $exception) {
    $reply->status = $exception->getCode();
    $reply->message = $exception->getMessage();
    $reply->trace = $exception->getTraceAsString();
}

header("Content-type: application/json");
echo json_encode($reply);