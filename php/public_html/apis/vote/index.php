<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/Classes/autoload.php";
require_once("/etc/apache2/vote/Secrets.php");
require_once dirname(__DIR__, 3) . "/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/lib/jwt.php";
require_once dirname(__DIR__, 3) . "/lib/uuid.php";

use JOHNTHEDEV\Game\{Vote, Player};

/**
 * api for interacting with Votes.
 *
 * @author John Johnson-Rodgers <john@johnthe.dev>
 */

//verify the session, start if inactive
if (session_status() !== PHP_SESSION_ACTIVE) {
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

    if ($method === "POST") {
        //enforce xsrf token
        verifyXsrf();
        if (empty($_SESSION["player"]) === true) {
            throw(new \InvalidArgumentException("You do not have permission to edit or add vote", 401));
        }

        //retrieves JSON package that was sent by the user and stores it in $requestContent using file_get_contents
        $requestContent = file_get_contents("php://input");

        //Decodes content and stores result in $requestObject
        $requestObject = json_decode($requestContent);
        //makes sure required fields are available
        if (!property_exists($requestObject, "voteTrue")) {
            throw(new \InvalidArgumentException("The Vote True field is empty.", 400));
        }
        if (!property_exists($requestObject, "voteStatementId")) {
            throw(new \InvalidArgumentException("The Vote Statement Id field is empty.", 400));
        }
        $existingVote = Vote::getVotesByPlayerIdAndStatementId($pdo, $_SESSION["player"]->getPlayerId(), $requestContent["voteStatementId"]);
        if($existingVote !==null){
            $existingVote->setVoteTrue($requestObject["voteTrue"]);
            $existingVote->update($pdo);
            //update reply
            $reply->data = $existingVote;
            $reply->message = "Vote has been changed.";
        } else{
            $vote = new Vote(generateUuidV4(), $requestObject["voteTrue"], $requestContent["voteStatementId"], $_SESSION["player"]->getPlayerId());
            $vote->insert($pdo);
            $reply->data = $vote;
            //update reply
            $reply->message = "New vote entry has been created.";
        }
    } else {
        throw (new InvalidArgumentException("Invalid HTTP method request", 405));
    }

} catch (\Exception|\TypeError $exception) {
    $reply->status = $exception->getCode();
    $reply->message = $exception->getMessage();
}
header("Content-type: application/json");
echo json_encode($reply);