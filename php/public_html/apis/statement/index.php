<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/Classes/autoload.php";
require_once("/etc/apache2/statement/Secrets.php");
require_once dirname(__DIR__, 3) . "/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/lib/jwt.php";
require_once dirname(__DIR__, 3) . "/lib/uuid.php";

use JOHNTHEDEV\Game\{Statement, Player, Vote};

/**
 * api for interacting with Statements.
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
            throw(new \InvalidArgumentException("You do not have permission to edit or add statement", 401));
        }

        //retrieves JSON package that was sent by the user and stores it in $requestContent using file_get_contents
        $requestContent = file_get_contents("php://input");

        //Decodes content and stores result in $requestContent
        $requestObjects = json_decode($requestContent);
        $insertedStatements = array();
        foreach ($requestObjects as $requestObject) {
            //makes sure required fields are available
            if (!property_exists($requestObject, "statementText")) {
                throw(new \InvalidArgumentException("The Statement Text field is empty.", 400));
            }
            if (!property_exists($requestObject, "statementTrue")) {
                throw(new \InvalidArgumentException("The Statement True field is empty.", 400));
            }
            if (!property_exists($requestObject, "statementPlayerId")) {
                throw(new \InvalidArgumentException("The Statement Player Id field is empty.", 400));
            }
            $statement = new Statement(generateUuidV4(), $requestObject["statementText"], $requestContent["statementTrue"], false, $_SESSION["player"]->getPlayerId());
            $statement->insert($pdo);
            $insertedStatements[] = $statement;
        }
        //update reply
        $reply->data =  $insertedStatements;
        $reply->message = "New statement entries have been created.";
    } else {
        throw (new InvalidArgumentException("Invalid HTTP method request", 405));
    }

} catch (\Exception|\TypeError $exception) {
    $reply->status = $exception->getCode();
    $reply->message = $exception->getMessage();
}
header("Content-type: application/json");
echo json_encode($reply);