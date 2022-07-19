<?php
namespace JOHNTHEDEV\Game;

require_once(dirname(__DIR__) . "/Classes/autoload.php");
require_once(dirname(__DIR__, 1) . "/vendor/autoload.php");

use Ramsey\Uuid\UuidInterface;


/**
 * Class message will store the entries in the knowledge system
 * @package JohnTheDev
 *
 * Description: This class will hold the game info.
 *
 * @author John Johnson-Rodgers <john@johnthe.dev>
 */
class Game implements \JsonSerializable {
    use ValidateUuid;
    use ValidateDate;

    /**
     * id for message; Primary key - Not null, uuid
     * @var UuidInterface $gameId
     */
    private UuidInterface $gameId;

    /**
     * Code for this game used to join active games - not null, string
     * @var string $gameCode
     */
    private string $gameCode;

    /**
     * date game was created - not null, \DateTime
     * @var \DateTime|string $gameCreated
     */
    private \DateTime|string $gameCreated;

    /**
     * date game was last acted in - not null, \DateTime
     * @var \DateTime|string $gameActivity
     */
    private \DateTime|string $gameActivity;

    /**
     * player whose turn it is- nullable, Player
     * @var ?Player $gameCurrentPlayer
     */
    private ?Player $gameCurrentPlayer;

    /**
     * current statement - nullable, Statement
     * @var ?Statement $gameCurrentStatement
     */
    private ?Statement $gameCurrentStatement;

    /**
     * team one score - nullable, int
     * @var $gameTeamOneScore ?int
     */
    private ?int $gameTeamOneScore;


    /***
     *       _____    ____    _   _    _____   _______   _____    _    _    _____   _______    ____    _____
     *      / ____|  / __ \  | \ | |  / ____| |__   __| |  __ \  | |  | |  / ____| |__   __|  / __ \  |  __ \
     *     | |      | |  | | |  \| | | (___      | |    | |__) | | |  | | | |         | |    | |  | | | |__) |
     *     | |      | |  | | | . ` |  \___ \     | |    |  _  /  | |  | | | |         | |    | |  | | |  _  /
     *     | |____  | |__| | | |\  |  ____) |    | |    | | \ \  | |__| | | |____     | |    | |__| | | | \ \
     *      \_____|  \____/  |_| \_| |_____/     |_|    |_|  \_\  \____/   \_____|    |_|     \____/  |_|  \_\
     */

    /**
     * Message constructor.
     * @param string $gameId
     * @param string $gameCode
     * @param \DateTime|string|null $gameCreated
     * @param \DateTime|string|null $gameActivity
     * @param string $gameCurrentPlayer
     * @param string $gameCurrentStatement
     * @param int $gameTeamOneScore
     * @param int $gameTeamTwoScore
     * @throws \InvalidArgumentException | \RangeException | \TypeError | \Exception if setters do not work
     */
    public function __construct(string $gameId, string $gameCode, string|\DateTime|null $gameCreated, string|\DateTime|null $gameActivity, string $gameCurrentPlayer, string $gameCurrentStatement, int $gameTeamOneScore, int $gameTeamTwoScore)
    {
        try {
            $this->setGameId($gameId);
            $this->setGameCode($gameCode);
            $this->setGameCreated($gameCreated);
            $this->setGameActivity($gameActivity);
            $this->setGameCurrentPlayer($gameCurrentPlayer);
            $this->setGameCurrentStatement($gameCurrentStatement);
            $this->setGameTeamOneScore($gameTeamOneScore);
            $this->setGameTeamTwoScore($gameTeamTwoScore);
        } catch (\InvalidArgumentException | \RangeException | \TypeError | \Exception $exception) {
            $exceptionType = get_class($exception);
            throw(new $exceptionType($exception->getMessage(), 0, $exception));
        }
    }

    /***
     *       _____   ______   _______   _______   ______   _____     _____       __   _____   ______   _______   _______   ______   _____     _____
     *      / ____| |  ____| |__   __| |__   __| |  ____| |  __ \   / ____|     / /  / ____| |  ____| |__   __| |__   __| |  ____| |  __ \   / ____|
     *     | |  __  | |__       | |       | |    | |__    | |__) | | (___      / /  | (___   | |__       | |       | |    | |__    | |__) | | (___
     *     | | |_ | |  __|      | |       | |    |  __|   |  _  /   \___ \    / /    \___ \  |  __|      | |       | |    |  __|   |  _  /   \___ \
     *     | |__| | | |____     | |       | |    | |____  | | \ \   ____) |  / /     ____) | | |____     | |       | |    | |____  | | \ \   ____) |
     *      \_____| |______|    |_|       |_|    |______| |_|  \_\ |_____/  /_/     |_____/  |______|    |_|       |_|    |______| |_|  \_\ |_____/
     */

    /**
     * Accessor for gameId, Not Null
     * Primary Key
     *
     * @return UuidInterface
     */
    public function getGameId(): UuidInterface
    {
        return ($this->gameId);
    }

    /**
     *Mutator Method for gameId, Not Null
     *Primary Key
     *
     * @param UuidInterface|string $gameId
     * @throws \Exception if $gameId is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setGameId(UuidInterface|string $gameId): void
    {
        try {
            //makes sure uuid is valid
            $uuid = self::validateUuid($gameId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            //throws exception if the uuid is invalid.
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Message Class Exception: setGameId: " . $exception->getMessage(), 0, $exception));
        }
        $this->gameId = $uuid;
    }

    /**
     * Accessor Method for gameCode
     *
     * @return string
     */
    public function getGameCode(): string
    {
        return ($this->gameCode);
    }

    /**
     * Mutator Method for gameCode
     *
     * @param string $newGameCode
     * @throws \Exception if $newGameCode is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setGameCode(string $newGameCode): void
    {
        //trim and filter out invalid input
        $newGameCode = trim($newGameCode);
        $newGameCode = filter_var($newGameCode, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        //checks if string length is appropriate
        if (strlen($newGameCode) > 60000) {
            throw (new \RangeException("Message Class Exception: GameCode is too long"));
        }
        $this->gameCode = $newGameCode;
    }

    /**
     * Accessor Method for gameCreated
     *
     * @return \DateTime|string
     */
    public function getGameCreated(): string|\DateTime
    {
        return ($this->gameCreated);
    }

    /**
     * Mutator Method for gameCreated
     *
     * @param string|\DateTime|null $newGameCreated
     * @throws \Exception if $newGameCreated is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setGameCreated(null|string|\DateTime $newGameCreated): void
    {
        //checks if $newGameCreated is null, if so set to current DateTime
        if($newGameCreated === null){
            $this->gameCreated = new \DateTime();
        } else {
            try {
                $newGameCreated = self::validateDateTime($newGameCreated);
            } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
                $exceptionType = get_class($exception);
                throw(new $exceptionType("Message Class Exception: setGameCreated: " . $exception->getMessage(), 0, $exception));
            }
            $this->gameCreated = $newGameCreated;
        }
    }

    /**
     * Accessor Method for gameActivity
     *
     * @return \DateTime|string
     */
    public function getGameActivity(): string|\DateTime
    {
        return ($this->gameActivity);
    }

    /**
     * Mutator Method for gameActivity
     *
     * @param string|\DateTime|null $newGameActivity
     * @throws \Exception if $newGameActivity is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setGameActivity(null|string|\DateTime $newGameActivity): void
    {
        //checks if $newGameActivity is null, if so set to current DateTime
        if($newGameActivity === null){
            $this->gameActivity = new \DateTime();
        } else {
            try {
                $newGameActivity = self::validateDateTime($newGameActivity);
            } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
                $exceptionType = get_class($exception);
                throw(new $exceptionType("Message Class Exception: setGameCreated: " . $exception->getMessage(), 0, $exception));
            }
            $this->gameActivity = $newGameActivity;
        }
    }

//
    /***
     *      __  __   ______   _______   _    _    ____    _____     _____
     *     |  \/  | |  ____| |__   __| | |  | |  / __ \  |  __ \   / ____|
     *     | \  / | | |__       | |    | |__| | | |  | | | |  | | | (___
     *     | |\/| | |  __|      | |    |  __  | | |  | | | |  | |  \___ \
     *     | |  | | | |____     | |    | |  | | | |__| | | |__| |  ____) |
     *     |_|  |_| |______|    |_|    |_|  |_|  \____/  |_____/  |_____/
     */
//
    /**
     * INSERT
     * Inserts Message into MySQL
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException if MySQL errors occur
     * @throws \TypeError if $PDO is not a PDO connection object
     */
    public function insert(\PDO $pdo): void
    {
        //create query template
        $query = "INSERT INTO message(gameId, gameCode, gameCreated) VALUES(:gameId, :gameCode, :gameCreated)";
        $statement = $pdo->prepare($query);
        //create parameters for query
        $parameters = [
            "gameId" => $this->gameId->getBytes(),
            "gameCode" => $this->gameCode,
            "gameCreated" => $this->gameCreated->format("Y-m-d H:i:s")
        ];
        $statement->execute($parameters);
    }
//
    /**
     * UPDATE
     * updates Message in MySQL database
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException when MySQL related error occurs
     * @throws \TypeError if $pdo is not pdo connection object
     */
    public function update(\PDO $pdo): void
    {
        //create query template
        $query = "UPDATE message SET gameCode=:gameCode, gameCreated=:gameCreated WHERE gameId = :gameId";
        $statement = $pdo->prepare($query);
        // set parameters to execute query
        $parameters = [
            "gameId" => $this->gameId->getBytes(),
            "gameCode" => $this->gameCode,
            "gameCreated" => $this->gameCreated->format("Y-m-d H:i:s")
        ];
        $statement->execute($parameters);
    }
//
    /**
     * DELETE
     * deletes Message from MySQL database
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException when mysql related errors occur
     * @throws \TypeError when $pdo is not a PDO object
     */
    public function delete(\PDO $pdo): void
    {
        //create query template
        $query = "DELETE FROM message WHERE gameId = :gameId";
        $statement = $pdo->prepare($query);
        //set parameters to execute query
        $parameters = ["gameId" => $this->gameId->getBytes()];
        $statement->execute($parameters);
    }
//

    /**
     * get message by gameId
     *
     * @param \PDO $pdo
     * @param string $gameId
     * @return Message|null
     * @throws \PDOException when mysql related errors occur
     * @throws \TypeError when variable doesn't follow typehints
     * @throws \Exception
     */
    public static function getMessageByGameId(\PDO $pdo, string $gameId): ?Message
    {
        //trim and filter out invalid input
        try {
            $gameId = self::validateUuid($gameId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Message Class Exception: getMessageByGameId: " . $exception->getMessage(), 0, $exception));
        }

        //create query template
        $query = "SELECT gameId, gameCode, gameCreated FROM message WHERE gameId = :gameId";
        $statement = $pdo->prepare($query);

        //set parameters to execute
        $parameters = ["gameId" => $gameId->getBytes()];
        $statement->execute($parameters);

        //grab message from MySQL
        try {
            $message = null;
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            $row = $statement->fetch();
            if ($row !== false) {
                $message = new Message($row["gameId"], $row["gameCode"], $row["gameCreated"]);
            }
        } catch (\Exception $exception) {
            //if row can't be converted rethrow it
            throw(new \PDOException($exception->getMessage(), 0, $exception));
        }
        return ($message);

    }

    /**
     * get all messages ordered by date
     *
     * @param \PDO $pdo
     * @return array
     * @throws \PDOException when mysql related errors occur
     * @throws \TypeError when variable doesn't follow typehints
     */
    public static function getAllMessages(\PDO $pdo): array
    {
        //create query template
        $query = "SELECT gameId, gameCode, gameCreated FROM message ORDER BY gameCreated DESC";
        $statement = $pdo->prepare($query);

        //set parameters to execute
        $parameters = [];
        $statement->execute($parameters);

        //grab message from MySQL
        $messages = array();
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        while (($row = $statement->fetch()) !== false) {
            try {
                $message = new Message($row["gameId"], $row["gameCode"], $row["gameCreated"]);
                $messages[] = $message;
            } catch (\Exception $exception) {
                //if row can't be converted rethrow it
                throw(new \PDOException($exception->getMessage(), 0, $exception));
            }
        }
        return ($messages);
    }

    /**
     * converts DateTime to string to serialize
     *
     * @return array converts DateTime to strings
     */
    public function jsonSerialize(): array
    {
        $fields = get_object_vars($this);
        if($this->gameId !== null) {
            $fields["gameId"] = $this->gameId->toString();
        }
        if ($this->gameCreated !== null) {
            $fields["gameCreated"] = $this->gameCreated->format("Y-m-d H:i:s");
        }
        return ($fields);
    }
}