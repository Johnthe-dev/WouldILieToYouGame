<?php
namespace JOHNTHEDEV\Game;

require_once(dirname(__DIR__) . "/Classes/autoload.php");
require_once(dirname(__DIR__, 1) . "/vendor/autoload.php");

use JetBrains\PhpStorm\ArrayShape;
use mysql_xdevapi\Exception;
use Ramsey\Uuid\UuidInterface;


/**
 * Class game will store the games
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
     * id for game; Primary key - Not null, uuid
     * @var UuidInterface|string $gameId
     */
    private UuidInterface|string $gameId;

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
     * playerId for player whose turn it is- nullable, UUID
     * @var UuidInterface|string|null $gameCurrentPlayerId
     */
    private UuidInterface|string|null $gameCurrentPlayerId;

    /**
     * current statement - nullable, Statement
     * @var UuidInterface|string|null $gameCurrentStatementId
     */
    private UuidInterface|string|null $gameCurrentStatementId;

    /**
     * gameCurrentState - not null, int
     * @var $gameCurrentState int
     */
    private int $gameCurrentState;

    /**
     * team one score - not null, int
     * @var $gameTeamOneScore int
     */
    private int $gameTeamOneScore;

    /**
     * team one score - not null, int
     * @var $gameTeamOneScore int
     */
    private int $gameTeamTwoScore;


    /***
     *       _____    ____    _   _    _____   _______   _____    _    _    _____   _______    ____    _____
     *      / ____|  / __ \  | \ | |  / ____| |__   __| |  __ \  | |  | |  / ____| |__   __|  / __ \  |  __ \
     *     | |      | |  | | |  \| | | (___      | |    | |__) | | |  | | | |         | |    | |  | | | |__) |
     *     | |      | |  | | | . ` |  \___ \     | |    |  _  /  | |  | | | |         | |    | |  | | |  _  /
     *     | |____  | |__| | | |\  |  ____) |    | |    | | \ \  | |__| | | |____     | |    | |__| | | | \ \
     *      \_____|  \____/  |_| \_| |_____/     |_|    |_|  \_\  \____/   \_____|    |_|     \____/  |_|  \_\
     */

    /**
     * Game constructor.
     * @param string $gameId
     * @param string $gameCode
     * @param \DateTime|string|null $gameCreated
     * @param \DateTime|string|null $gameActivity
     * @param string $gameCurrentPlayerId
     * @param string $gameCurrentStatementId
     * @param ?int $gameCurrentState
     * @param ?int $gameTeamOneScore
     * @param ?int $gameTeamTwoScore
     * @throws \InvalidArgumentException | \RangeException | \TypeError | \Exception if setters do not work
     */
    public function __construct(string $gameId, string $gameCode, string|\DateTime|null $gameCreated, string|\DateTime|null $gameActivity, string $gameCurrentPlayerId, string $gameCurrentStatementId, ?int $gameCurrentState, ?int $gameTeamOneScore, ?int $gameTeamTwoScore)
    {
        try {
            $this->setGameId($gameId);
            $this->setGameCode($gameCode);
            $this->setGameCreated($gameCreated);
            $this->setGameActivity($gameActivity);
            $this->setGameCurrentPlayerId($gameCurrentPlayerId);
            $this->setGameCurrentStatementId($gameCurrentStatementId);
            $this->setGameCurrentState($gameCurrentState);
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
            throw(new $exceptionType("Game Class Exception: setGameId: " . $exception->getMessage(), 0, $exception));
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
        if (strlen($newGameCode) > 6) {
            throw (new \RangeException("Game Class Exception: GameCode is too long"));
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
                throw(new $exceptionType("Game Class Exception: setGameCreated: " . $exception->getMessage(), 0, $exception));
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
                throw(new $exceptionType("Game Class Exception: setGameCreated: " . $exception->getMessage(), 0, $exception));
            }
            $this->gameActivity = $newGameActivity;
        }
    }

    /**
     * Accessor Method for gameCurrentPlayerId
     *
     * @return UuidInterface|null
     */
    public function getGameCurrentPlayerId(): UuidInterface|null
    {
        return ($this->gameCurrentPlayerId);
    }

    /**
     * Mutator Method for gameCurrentPlayer
     *
     * @param string|UuidInterface|null $newGameCurrentPlayerId
     * @throws \Exception if $newGameCurrentPlayer is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setGameCurrentPlayerId(string|UuidInterface|null $newGameCurrentPlayerId): void
    {
        if($newGameCurrentPlayerId===null){
            $this->gameCurrentPlayerId = null;
        } else {
            try {
                //makes sure uuid is valid
                $uuid = self::validateUuid($newGameCurrentPlayerId);
            } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
                //throws exception if the uuid is invalid.
                $exceptionType = get_class($exception);
                throw(new $exceptionType("Game Class Exception: setGameCurrentPlayerId: " . $exception->getMessage(), 0, $exception));
            }
            $this->gameCurrentPlayerId = $uuid;
        }
    }

    /**
     * Accessor Method for gameCurrentStatement
     *
     * @return UuidInterface|null
     */
    public function getGameCurrentStatementId(): UuidInterface|null
    {
        return ($this->gameCurrentStatementId);
    }

    /**
     * Mutator Method for gameCurrentStatementId
     *
     * @param string|UuidInterface|null $newGameCurrentStatementId
     * @throws \Exception if $newGameCurrentStatementId is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setGameCurrentStatementId(string|UuidInterface|null $newGameCurrentStatementId): void
    {
        if($newGameCurrentStatementId===null){
            $this->gameCurrentStatementId = null;
        } else {
            try {
                //makes sure uuid is valid
                $uuid = self::validateUuid($newGameCurrentStatementId);
            } catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
                //throws exception if the uuid is invalid.
                $exceptionType = get_class($exception);
                throw(new $exceptionType("Game Class Exception: setGameCurrentStatementId: " . $exception->getMessage(), 0, $exception));
            }
            $this->gameCurrentStatementId = $uuid;
        }
    }

    /**
     * Accessor Method for gameCurrentState
     *
     * @return int
     */
    public function getGameCurrentState(): int
    {
        return ($this->gameCurrentState);
    }

    /**
     * Mutator Method for gameCurrentState
     *
     * @param ?int $newGameCurrentState
     * @throws \Exception if $newGameCurrentState is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setGameCurrentState(?int $newGameCurrentState): void
    {
        //filter out invalid input and check if value is empty
        if(empty($newGameCurrentState)){
            $newGameCurrentState = 0;
        }else{
            $newGameCurrentState = filter_var($newGameCurrentState, FILTER_VALIDATE_INT);
            if($newGameCurrentState===false){
                throw new \InvalidArgumentException("Game Class Exception: Team One Score is invalid");
            }
        }

        $this->gameCurrentState = $newGameCurrentState;
    }

    /**
     * Accessor Method for gameTeamOneScore
     *
     * @return int
     */
    public function getGameTeamOneScore(): int
    {
        return ($this->gameTeamOneScore);
    }

    /**
     * Mutator Method for gameTeamOneScore
     *
     * @param ?int $newGameTeamOneScore
     * @throws \Exception if $newGameTeamOneScore is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setGameTeamOneScore(?int $newGameTeamOneScore): void
    {
        //filter out invalid input and check if value is empty
        if(empty($newGameTeamOneScore)){
            $newGameTeamOneScore = 0;
        }else{
            $newGameTeamOneScore = filter_var($newGameTeamOneScore, FILTER_VALIDATE_INT);
            if($newGameTeamOneScore===false){
                throw new \InvalidArgumentException("Game Class Exception: Team One Score is invalid");
            }
        }

        $this->gameTeamOneScore = $newGameTeamOneScore;
    }


    /**
     * Accessor Method for gameTeamTwoScore
     *
     * @return int
     */
    public function getGameTeamTwoScore(): int
    {
        return ($this->gameTeamTwoScore);
    }

    /**
     * Mutator Method for gameTeamTwoScore
     *
     * @param ?int $newGameTeamTwoScore
     * @throws \Exception if $newGameTeamTwoScore is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setGameTeamTwoScore(?int $newGameTeamTwoScore): void
    {
        //filter out invalid input and check if value is empty
        if(empty($newGameTeamTwoScore)){
            $newGameTeamTwoScore = 0;
        }else{
            $newGameTeamTwoScore = filter_var($newGameTeamTwoScore, FILTER_VALIDATE_INT);
            if($newGameTeamTwoScore===false){
                throw new \InvalidArgumentException("Game Class Exception: Team Two Score is invalid");
            }
        }

        $this->gameTeamTwoScore = $newGameTeamTwoScore;
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
     * Inserts Game into MySQL
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException if MySQL errors occur
     * @throws \TypeError if $PDO is not a PDO connection object
     */
    public function insert(\PDO $pdo): void
    {
        //create query template
        $query = "INSERT INTO game (gameId, gameCode, gameCreated, gameActivity, gameCurrentPlayerId, gameCurrentStatementId, 
                 gameCurrentState, gameTeamOneScore, gameTeamTwoScore) VALUES(:gameId, :gameCode, :gameCreated, :gameActivity, 
                  :gameCurrentPlayerId, :gameCurrentStatementId, :gameCurrentState, :gameTeamOneScore, :gameTeamTwoScore)";
        $statement = $pdo->prepare($query);
        //create parameters for query
        $parameters = $this->getParameters();
        $statement->execute($parameters);
    }
//
    /**
     * UPDATE
     * updates Game in MySQL database
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException when MySQL related error occurs
     * @throws \TypeError if $pdo is not pdo connection object
     */
    public function update(\PDO $pdo): void
    {
        //create query template
        $query = "UPDATE game SET gameCode = :gameCode, gameCreated = :gameCreated, gameActivity = :gameActivity,
                    gameCurrentPlayerId = :gameCurrentPlayerId, gameCurrentStatementId = :gameCurrentStatementId, 
                    gameCurrentState = :gameCurrentState, gameTeamOneScore = :gameTeamOneScore, 
                    gameTeamTwoScore = :gameTeamTwoScore WHERE gameId = :gameId
 ";
        $statement = $pdo->prepare($query);
        // set parameters to execute query
        $parameters = $this->getParameters();
        $statement->execute($parameters);
    }
//
    /**
     * DELETE
     * deletes Game from MySQL database
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException when mysql related errors occur
     * @throws \TypeError|\Exception when $pdo is not a PDO object or another Exception
     */
    public function delete(\PDO $pdo): void
    {
        $players = Player::getPlayersByGameId($pdo, $this->gameId);
        foreach($players as $player){
            $player->delete($pdo);
        }
        //create query template
        $query = "DELETE FROM game WHERE gameId = :gameId";
        $statement = $pdo->prepare($query);
        //set parameters to execute query
        $parameters = ["gameId" => $this->gameId->getBytes()];
        $statement->execute($parameters);
    }

    #[ArrayShape(["gameId" => "string", "gameCode" => "string", "gameCreated" => "string", "gameActivity" => "string",
        "gameCurrentPlayerId" => "mixed", "gameCurrentStatementId" => "mixed", "gameCurrentState" => "int",
        "gameTeamOneScore" => "int", "gameTeamTwoScore" => "int"])] private function getParameters(): array {

        $currentPlayerId = $this->gameCurrentPlayerId === null ? null : $this->gameCurrentPlayerId->getBytes();
        $currentStatementId = $this->gameCurrentStatementId === null ? null : $this->gameCurrentStatementId->getBytes();
        return [
            "gameId" => $this->gameId->getBytes(),
            "gameCode" => $this->gameCode,
            "gameCreated" => $this->gameCreated->format("Y-m-d H:i:s"),
            "gameActivity" => $this->gameActivity->format("Y-m-d H:i:s"),
            "gameCurrentPlayerId" => $currentPlayerId,
            "gameCurrentStatementId" => $currentStatementId,
            "gameCurrentState" => $this->gameCurrentState,
            "gameTeamOneScore" => $this->gameTeamOneScore,
            "gameTeamTwoScore" => $this->gameTeamTwoScore
        ];
    }
//

    /**
     * get game by gameId
     *
     * @param \PDO $pdo
     * @param string $gameId
     * @return Game|null
     * @throws \PDOException when mysql related errors occur
     * @throws \TypeError when variable doesn't follow typehints
     * @throws \Exception
     */
    public static function getGameByGameId(\PDO $pdo, string $gameId): ?Game
    {
        //trim and filter out invalid input
        try {
            $gameId = self::validateUuid($gameId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Game Class Exception: getGameByGameId: " . $exception->getMessage(), 0, $exception));
        }

        //create query template
        $query = "SELECT gameId, gameCode, gameCreated, gameActivity, gameCurrentPlayerId, gameCurrentStatementId, 
                 gameCurrentState, gameTeamOneScore, gameTeamTwoScore FROM game WHERE gameId = :gameId";
        $statement = $pdo->prepare($query);

        //set parameters to execute
        $parameters = ["gameId" => $gameId->getBytes()];
        $statement->execute($parameters);

        //grab game from MySQL
        try {
            $game = null;
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            $row = $statement->fetch();
            if ($row !== false) {
                $game = new Game($row["gameId"], $row["gameCode"], $row["gameCreated"], $row["gameActivity"],
                    $row["gameCurrentPlayerId"], $row["gameCurrentStatementId"], $row["gameCurrentState"],
                    $row["gameTeamOneScore"], $row["gameTeamTwoScore"]);
            }
        } catch (\Exception $exception) {
            //if row can't be converted rethrow it
            throw(new \PDOException($exception->getMessage(), 0, $exception));
        }
        return ($game);

    }

    /**
     * converts DateTimes and guids to string to serialize
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
        if ($this->gameActivity !== null) {
            $fields["gameActivity"] = $this->gameActivity->format("Y-m-d H:i:s");
        }
        return ($fields);
    }
}