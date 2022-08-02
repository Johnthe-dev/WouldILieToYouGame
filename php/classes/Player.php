<?php
namespace JOHNTHEDEV\Game;

require_once(dirname(__DIR__) . "/Classes/autoload.php");
require_once(dirname(__DIR__, 1) . "/vendor/autoload.php");

use JetBrains\PhpStorm\ArrayShape;
use Ramsey\Uuid\UuidInterface;


/**
 * Class player will store the players
 * @package JohnTheDev
 *
 * Description: This class will hold the player info.
 *
 * @author John Johnson-Rodgers <john@johnthe.dev>
 */
class Player implements \JsonSerializable {
    use ValidateUuid;
    use ValidateDate;

    /**
     * id for player; Primary key - Not null, uuid
     * @var UuidInterface $playerId
     */
    private UuidInterface $playerId;

    /**
     * Uuid for the Game this player is in - not null, uuid
     * @var UuidInterface $playerGameId
     */
    private UuidInterface $playerGameId;

    /**
     * player name - not null, string
     * @var string $playerName
     */
    private string $playerName;

    /**
     * player team number, 1 or 2 - not null, int
     * @var int $playerTeamNumber
     */
    private int $playerTeamNumber;

    /**
     * whether the player has participated this round - not null, boolean
     * @var bool $playerPlayed
     */
    private bool $playerPlayed;

    /**
     * current statement - nullable, Statement
     * @var string|null|\DateTime $playerLastModified
     */
    private string|null|\DateTime $playerLastModified;

    /***
     *       _____    ____    _   _    _____   _______   _____    _    _    _____   _______    ____    _____
     *      / ____|  / __ \  | \ | |  / ____| |__   __| |  __ \  | |  | |  / ____| |__   __|  / __ \  |  __ \
     *     | |      | |  | | |  \| | | (___      | |    | |__) | | |  | | | |         | |    | |  | | | |__) |
     *     | |      | |  | | | . ` |  \___ \     | |    |  _  /  | |  | | | |         | |    | |  | | |  _  /
     *     | |____  | |__| | | |\  |  ____) |    | |    | | \ \  | |__| | | |____     | |    | |__| | | | \ \
     *      \_____|  \____/  |_| \_| |_____/     |_|    |_|  \_\  \____/   \_____|    |_|     \____/  |_|  \_\
     */

    /**
     * Player constructor.
     * @param string $playerId
     * @param string $playerGameId
     * @param string $playerName
     * @param int $playerTeamNumber
     * @param bool $playerPlayed
     * @param string|\DateTime|null $playerLastModified
     * @throws \InvalidArgumentException | \RangeException | \TypeError | \Exception if setters do not work
     */
    public function __construct(string $playerId, string $playerGameId, string $playerName, int $playerTeamNumber, 
                                bool $playerPlayed, string|\DateTime|null $playerLastModified)
    {
        try {
            $this->setPlayerId($playerId);
            $this->setPlayerGameId($playerGameId);
            $this->setPlayerName($playerName);
            $this->setPlayerTeamNumber($playerTeamNumber);
            $this->setPlayerPlayed($playerPlayed);
            $this->setPlayerLastModified($playerLastModified);
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
     * Accessor for playerId, Not Null
     * Primary Key
     *
     * @return UuidInterface
     */
    public function getPlayerId(): UuidInterface
    {
        return ($this->playerId);
    }

    /**
     *Mutator Method for playerId, Not Null
     *Primary Key
     *
     * @param UuidInterface|string $playerId
     * @throws \Exception if $playerId is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setPlayerId(UuidInterface|string $playerId): void
    {
        try {
            //makes sure uuid is valid
            $uuid = self::validateUuid($playerId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            //throws exception if the uuid is invalid.
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Player Class Exception: setPlayerId: " . $exception->getMessage(), 0, $exception));
        }
        $this->playerId = $uuid;
    }

    /**
     * Accessor for playerGameId, Not Null
     * Primary Key
     *
     * @return UuidInterface
     */
    public function getPlayerGameId(): UuidInterface
    {
        return ($this->playerGameId);
    }

    /**
     *Mutator Method for playerGameId, Not Null
     *Primary Key
     *
     * @param UuidInterface|string $playerGameId
     * @throws \Exception if $playerGameId is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setPlayerGameId(UuidInterface|string $playerGameId): void
    {
        try {
            //makes sure uuid is valid
            $uuid = self::validateUuid($playerGameId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            //throws exception if the uuid is invalid.
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Player Class Exception: setPlayerGameId: " . $exception->getMessage(), 0, $exception));
        }
        $this->playerGameId = $uuid;
    }

    /**
     * Accessor Method for playerName
     *
     * @return string
     */
    public function getPlayerName(): string
    {
        return ($this->playerName);
    }

    /**
     * Mutator Method for playerName
     *
     * @param string $newPlayerName
     * @throws \Exception if $newPlayerName is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setPlayerName(string $newPlayerName): void
    {
        //trim and filter out invalid input
        $newPlayerName = trim($newPlayerName);
        $newPlayerName = filter_var($newPlayerName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        //checks if string length is appropriate
        if (strlen($newPlayerName) > 32) {
            throw (new \RangeException("Player Class Exception: Player Name is too long"));
        }
        $this->playerName = $newPlayerName;
    }

    /**
     * Accessor Method for playerTeamNumber
     *
     * @return int
     */
    public function getPlayerTeamNumber(): int
    {
        return ($this->playerTeamNumber);
    }

    /**
     * Mutator Method for playerTeamNumber
     *
     * @param int $newPlayerTeamNumber
     * @throws \Exception if $newPlayerTeamNumber is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setPlayerTeamNumber(int $newPlayerTeamNumber): void
    {
        //filter out invalid input
        $newPlayerTeamNumber = filter_var($newPlayerTeamNumber, FILTER_VALIDATE_INT);
        if($newPlayerTeamNumber === false || $newPlayerTeamNumber > 2 || $newPlayerTeamNumber < 1){
            throw new \InvalidArgumentException("Player Class Exception: Team Number is invalid");
        }
        $this->playerTeamNumber = $newPlayerTeamNumber;
    }

    /**
     * Accessor Method for playerPlayed
     *
     * @return bool
     */
    public function getPlayerPlayed(): bool
    {
        return ($this->playerPlayed);
    }

    /**
     * Mutator Method for playerPlayed
     *
     * @param bool $newPlayerPlayed
     * @throws \Exception if $newPlayerPlayed is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setPlayerPlayed(bool $newPlayerPlayed): void
    {
        //filter out invalid input
        $newPlayerPlayed = filter_var($newPlayerPlayed, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        if($newPlayerPlayed==null){
            throw new \InvalidArgumentException("Player Class Exception: Player Played is invalid");
        }
        $this->playerPlayed = $newPlayerPlayed;
    }

    /**
     * Accessor Method for playerLastModified
     *
     * @return \DateTime|string
     */
    public function getPlayerLastModified(): string|\DateTime
    {
        return ($this->playerLastModified);
    }

    /**
     * Mutator Method for playerLastModified
     *
     * @param string|\DateTime|null $newPlayerLastModified
     * @throws \Exception if $newPlayerLastModified is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setPlayerLastModified(null|string|\DateTime $newPlayerLastModified): void
    {
        //checks if $newPlayerLastModified is null, if so set to current DateTime
        if($newPlayerLastModified === null){
            $this->playerLastModified = new \DateTime();
        } else {
            try {
                $newPlayerLastModified = self::validateDateTime($newPlayerLastModified);
            } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
                $exceptionType = get_class($exception);
                throw(new $exceptionType("Player Class Exception: setPlayerLastModified: " . $exception->getMessage(), 0, $exception));
            }
            $this->playerLastModified = $newPlayerLastModified;
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
     * Inserts Player into MySQL
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException if MySQL errors occur
     * @throws \TypeError if $PDO is not a PDO connection object
     */
    public function insert(\PDO $pdo): void
    {
        //create query template
        $query = "INSERT INTO player (playerId, playerGameId, playerName, playerTeamNumber, playerPlayed, playerLastModified) 
                    VALUES(:playerId, :playerGameId, :playerName, :playerTeamNumber, :playerPlayed, :playerLastModified)";
        $statement = $pdo->prepare($query);
        //create parameters for query
        $parameters = $this->getParameters();
        $statement->execute($parameters);
    }
//
    /**
     * UPDATE
     * updates Player in MySQL database
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException when MySQL related error occurs
     * @throws \TypeError if $pdo is not pdo connection object
     */
    public function update(\PDO $pdo): void
    {
        //create query template
        $query = "UPDATE player SET playerId = :playerId, playerGameId = :playerGameId, playerName = :playerName, 
                  playerTeamNumber = :playerTeamNumber, playerPlayed = :playerPlayed, 
                  playerLastModified = :playerLastModified WHERE playerId = :playerId
 ";
        $statement = $pdo->prepare($query);
        // set parameters to execute query
        $parameters = $this->getParameters();
        $statement->execute($parameters);
    }
//
    /**
     * DELETE
     * deletes Player from MySQL database
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException when mysql related errors occur
     * @throws \TypeError when $pdo is not a PDO object
     */
    public function delete(\PDO $pdo): void
    {
        //create query template
        $query = "DELETE FROM player WHERE playerId = :playerId";
        $statement = $pdo->prepare($query);
        //set parameters to execute query
        $parameters = ["playerId" => $this->playerId->getBytes()];
        $statement->execute($parameters);
    }

    #[ArrayShape(["playerId" => "string", "playerGameId" => "string", "playerName" => "string", "playerTeamNumber" => "int",
        "playerPlayed" => "bool", "playerLastModified" => "string"])] private function getParameters(): array {
        return [
            "playerId" => $this->playerId->getBytes(),
            "playerGameId" => $this->playerGameId->getBytes(),
            "playerName" => $this->playerName,
            "playerTeamNumber" => $this->playerTeamNumber,
            "playerPlayed" => $this->playerPlayed,
            "playerLastModified" => $this->playerLastModified->format("Y-m-d H:i:s"),
        ];
    }
//

    /**
     * get player by playerId
     *
     * @param \PDO $pdo
     * @param string $playerId
     * @return Player|null
     * @throws \PDOException when mysql related errors occur
     * @throws \TypeError when variable doesn't follow typehints
     * @throws \Exception
     */
    public static function getPlayerByPlayerId(\PDO $pdo, string $playerId): ?Player
    {
        //trim and filter out invalid input
        try {
            $playerId = self::validateUuid($playerId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Player Class Exception: getPlayerByPlayerId: " . $exception->getMessage(), 0, $exception));
        }

        //create query template
        $query = "SELECT playerId, playerGameId, playerName, playerTeamNumber, playerPlayed, playerLastModified
                    FROM player WHERE playerId = :playerId";
        $statement = $pdo->prepare($query);

        //set parameters to execute
        $parameters = ["playerId" => $playerId->getBytes()];
        $statement->execute($parameters);

        //grab player from MySQL
        try {
            $player = null;
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            $row = $statement->fetch();
            if ($row !== false) {
                $player = new Player($row["playerId"], $row["playerGameId"], $row["playerName"], $row["playerTeamNumber"],
                    $row["playerPlayed"], $row["playerLastModified"]);
            }
        } catch (\Exception $exception) {
            //if row can't be converted rethrow it
            throw(new \PDOException($exception->getMessage(), 0, $exception));
        }
        return ($player);

    }

    /**
     * get player by playerGameId
     *
     * @param \PDO $pdo
     * @param string $playerGameId
     * @return Player|null
     * @throws \PDOException when mysql related errors occur
     * @throws \TypeError when variable doesn't follow typehints
     * @throws \Exception
     */
    public static function getPlayersByGameId(\PDO $pdo, string $playerGameId): array
    {
        //trim and filter out invalid input
        try {
            $playerGameId = self::validateUuid($playerGameId);
        } catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Player Class Exception: getPlayerByPlayerId: " . $exception->getMessage(), 0, $exception));
        }

        //create query template
        $query = "SELECT playerId, playerGameId, playerName, playerTeamNumber, playerPlayed, playerLastModified
                    FROM player WHERE playerGameId = :playerGameId";
        $statement = $pdo->prepare($query);

        //set parameters to execute
        $parameters = ["playerGameId" => $playerGameId->getBytes()];
        $statement->execute($parameters);

        //grab players from MySQL
        $players = array();

        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        while (($row = $statement->fetch()) !== false) {
            try {
                $player = new Player($row["playerId"], $row["playerGameId"], $row["playerName"], $row["playerTeamNumber"],
                    $row["playerPlayed"], $row["playerLastModified"]);
                $players[] = $player;
            } catch (\Exception $exception) {
                //if row can't be converted rethrow it
                throw(new \PDOException($exception->getMessage(), 0, $exception));
            }
        }
        return ($players);

    }

    /**
     * converts DateTimes and guids to string to serialize
     *
     * @return array converts DateTime to strings
     */
    public function jsonSerialize(): array
    {
        $fields = get_object_vars($this);
        if($this->playerId !== null) {
            $fields["playerId"] = $this->playerId->toString();
        }
        if($this->playerGameId !== null) {
            $fields["playerGameId"] = $this->playerGameId->toString();
        }
        if ($this->playerLastModified !== null) {
            $fields["playerLastModified"] = $this->playerLastModified->format("Y-m-d H:i:s");
        }
        return ($fields);
    }
}