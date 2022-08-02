<?php
namespace JOHNTHEDEV\Game;

require_once(dirname(__DIR__) . "/Classes/autoload.php");
require_once(dirname(__DIR__, 1) . "/vendor/autoload.php");

use JetBrains\PhpStorm\ArrayShape;
use Ramsey\Uuid\UuidInterface;


/**
 * Class statement will store the statements
 * @package JohnTheDev
 *
 * Description: This class will hold the statement info.
 *
 * @author John Johnson-Rodgers <john@johnthe.dev>
 */
class Statement implements \JsonSerializable {
    use ValidateUuid;
    use ValidateDate;

    /**
     * id for statement; Primary key - Not null, uuid
     * @var UuidInterface $statementId
     */
    private UuidInterface $statementId;

    /**
     * statement text - not null, string
     * @var string $statementText
     */
    private string $statementText;

    /**
     * whether the statement is true - not null, boolean
     * @var bool $statementTrue
     */
    private bool $statementTrue;

    /**
     * whether the statement has been used this round - not null, boolean
     * @var bool $statementUsed
     */
    private bool $statementUsed;
    
    /**
     * Uuid for the Player this statement is for - not null, uuid
     * @var UuidInterface $statementPlayerId
     */
    private UuidInterface $statementPlayerId;


    /***
     *       _____    ____    _   _    _____   _______   _____    _    _    _____   _______    ____    _____
     *      / ____|  / __ \  | \ | |  / ____| |__   __| |  __ \  | |  | |  / ____| |__   __|  / __ \  |  __ \
     *     | |      | |  | | |  \| | | (___      | |    | |__) | | |  | | | |         | |    | |  | | | |__) |
     *     | |      | |  | | | . ` |  \___ \     | |    |  _  /  | |  | | | |         | |    | |  | | |  _  /
     *     | |____  | |__| | | |\  |  ____) |    | |    | | \ \  | |__| | | |____     | |    | |__| | | | \ \
     *      \_____|  \____/  |_| \_| |_____/     |_|    |_|  \_\  \____/   \_____|    |_|     \____/  |_|  \_\
     */

    /**
     * Statement constructor.
     * @param string $statementId
     * @param string $statementText
     * @param bool $statementTrue
     * @param bool $statementUsed
     * @param string $statementPlayerId
     * @throws \InvalidArgumentException | \RangeException | \TypeError | \Exception if setters do not work
     */
    public function __construct(string $statementId, string $statementText, bool $statementTrue, bool $statementUsed, string $statementPlayerId)
    {
        try {
            $this->setStatementId($statementId);
            $this->setStatementText($statementText);
            $this->setStatementTrue($statementTrue);
            $this->setStatementUsed($statementUsed);
            $this->setStatementPlayerId($statementPlayerId);
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
     * Accessor for statementId, Not Null
     * Primary Key
     *
     * @return UuidInterface
     */
    public function getStatementId(): UuidInterface
    {
        return ($this->statementId);
    }

    /**
     *Mutator Method for statementId, Not Null
     *Primary Key
     *
     * @param UuidInterface|string $statementId
     * @throws \Exception if $statementId is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setStatementId(UuidInterface|string $statementId): void
    {
        try {
            //makes sure uuid is valid
            $uuid = self::validateUuid($statementId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            //throws exception if the uuid is invalid.
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Statement Class Exception: setStatementId: " . $exception->getMessage(), 0, $exception));
        }
        $this->statementId = $uuid;
    }

    /**
     * Accessor Method for statementText
     *
     * @return string
     */
    public function getStatementText(): string
    {
        return ($this->statementText);
    }

    /**
     * Mutator Method for statementText
     *
     * @param string $newStatementText
     * @throws \Exception if $newStatementText is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setStatementText(string $newStatementText): void
    {
        //trim and filter out invalid input
        $newStatementText = trim($newStatementText);
        $newStatementText = filter_var($newStatementText, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        //checks if string length is appropriate
        if (strlen($newStatementText) > 280) {
            throw (new \RangeException("Statement Class Exception: Statement Text is too long"));
        }
        if(strlen($newStatementText < 5)){
            throw (new \RangeException("Statement Class Exception: Statement Text is too short"));
        }
        $this->statementText = $newStatementText;
    }

    /**
     * Accessor Method for statementTrue
     *
     * @return bool
     */
    public function getStatementTrue(): bool
    {
        return ($this->statementTrue);
    }

    /**
     * Mutator Method for statementTrue
     *
     * @param bool $newStatementTrue
     * @throws \Exception if $newStatementTrue is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setStatementTrue(bool $newStatementTrue): void
    {
        //filter out invalid input
        $newStatementTrue = filter_var($newStatementTrue, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        if($newStatementTrue==null){
            throw new \InvalidArgumentException("Statement Class Exception: Statement True is invalid");
        }
        $this->statementTrue = $newStatementTrue;
    }

    /**
     * Accessor Method for statementUsed
     *
     * @return bool
     */
    public function getStatementUsed(): bool
    {
        return ($this->statementUsed);
    }

    /**
     * Mutator Method for statementUsed
     *
     * @param bool $newStatementUsed
     * @throws \Exception if $newStatementUsed is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setStatementUsed(bool $newStatementUsed): void
    {
        //filter out invalid input
        $newStatementUsed = filter_var($newStatementUsed, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        if($newStatementUsed==null){
            throw new \InvalidArgumentException("Statement Class Exception: Statement Used is invalid");
        }
        $this->statementUsed = $newStatementUsed;
    }

    /**
     * Accessor for statementPlayerId, Not Null
     * Primary Key
     *
     * @return UuidInterface
     */
    public function getStatementPlayerId(): UuidInterface
    {
        return ($this->statementPlayerId);
    }

    /**
     *Mutator Method for statementPlayerId, Not Null
     *Primary Key
     *
     * @param UuidInterface|string $statementPlayerId
     * @throws \Exception if $statementPlayerId is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setStatementPlayerId(UuidInterface|string $statementPlayerId): void
    {
        try {
            //makes sure uuid is valid
            $uuid = self::validateUuid($statementPlayerId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            //throws exception if the uuid is invalid.
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Statement Class Exception: setStatementPlayerId: " . $exception->getMessage(), 0, $exception));
        }
        $this->statementPlayerId = $uuid;
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
     * Inserts Statement into MySQL
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException if MySQL errors occur
     * @throws \TypeError if $PDO is not a PDO connection object
     */
    public function insert(\PDO $pdo): void
    {
        //create query template
        $query = "INSERT INTO statement (statementId, statementText, statementTrue, statementUsed, statementPlayerId) 
                    VALUES(:statementId, :statementText, :statementTrue, :statementUsed, :statementPlayerId)";
        $statement = $pdo->prepare($query);
        //create parameters for query
        $parameters = $this->getParameters();
        $statement->execute($parameters);
    }
//
    /**
     * UPDATE
     * updates Statement in MySQL database
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException when MySQL related error occurs
     * @throws \TypeError if $pdo is not pdo connection object
     */
    public function update(\PDO $pdo): void
    {
        //create query template
        $query = "UPDATE statement SET statementId = :statementId, statementText = :statementText, statementTrue = :statementTrue,
                      statementUsed = :statementUsed, statementPlayerId = :statementPlayerId WHERE statementId = :statementId";
        $statement = $pdo->prepare($query);
        // set parameters to execute query
        $parameters = $this->getParameters();
        $statement->execute($parameters);
    }
//
    /**
     * DELETE
     * deletes Statement from MySQL database
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException when mysql related errors occur
     * @throws \TypeError when $pdo is not a PDO object
     */
    public function delete(\PDO $pdo): void
    {
        //create query template
        $query = "DELETE FROM statement WHERE statementId = :statementId";
        $statement = $pdo->prepare($query);
        //set parameters to execute query
        $parameters = ["statementId" => $this->statementId->getBytes()];
        $statement->execute($parameters);
    }

    #[ArrayShape(["statementId" => "string", "statementText" => "string", "statementTrue" => "bool", "statementUsed" => "bool",
        "statementPlayerId" => "string"])] private function getParameters(): array {
        return [
            "statementId" => $this->statementId->getBytes(),
            "statementText" => $this->statementText,
            "statementTrue" => $this->statementTrue,
            "statementUsed" => $this->statementUsed,
            "statementPlayerId" => $this->statementPlayerId->getBytes()
        ];
    }
//

    /**
     * get statement by statementId
     *
     * @param \PDO $pdo
     * @param string $statementId
     * @return Statement|null
     * @throws \PDOException when mysql related errors occur
     * @throws \TypeError when variable doesn't follow typehints
     * @throws \Exception
     */
    public static function getStatementByStatementId(\PDO $pdo, string $statementId): ?Statement
    {
        //trim and filter out invalid input
        try {
            $statementId = self::validateUuid($statementId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Statement Class Exception: getStatementByStatementId: " . $exception->getMessage(), 0, $exception));
        }

        //create query template
        $query = "SELECT statementId, statementText, statementTrue, statementUsed, statementPlayerId
                    FROM statement WHERE statementId = :statementId";
        $statementSql = $pdo->prepare($query);

        //set parameters to execute
        $parameters = ["statementId" => $statementId->getBytes()];
        $statementSql->execute($parameters);

        //grab statement from MySQL
        try {
            $statement = null;
            $statementSql->setFetchMode(\PDO::FETCH_ASSOC);
            $row = $statementSql->fetch();
            if ($row !== false) {
                $statement = new Statement($row["statementId"], $row["statementText"], $row["statementTrue"], $row["statementUsed"], $row["statementPlayerId"]
                );
            }
        } catch (\Exception $exception) {
            //if row can't be converted rethrow it
            throw(new \PDOException($exception->getMessage(), 0, $exception));
        }
        return ($statement);

    }

    /**
     * get statement by statementGameId
     *
     * @param \PDO $pdo
     * @param string $statementPlayerId
     * @return Statement|null
     * @throws \PDOException when mysql related errors occur
     * @throws \TypeError when variable doesn't follow typehints
     * @throws \Exception
     */
    public static function getRandomStatementByPlayerId(\PDO $pdo, string $statementPlayerId): array
    {
        //trim and filter out invalid input
        try {
            $statementPlayerId = self::validateUuid($statementPlayerId);
        } catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Statement Class Exception: getStatementByStatementId: " . $exception->getMessage(), 0, $exception));
        }
        //truth or lie
        $true = rand(1,2)==1;

        //create query template
        $query = "SELECT statementId, statementText, statementTrue, statementUsed, statementPlayerId
                    FROM statement WHERE statementId = :statementId";
        $statement = $pdo->prepare($query);

        //set parameters to execute
        $parameters = ["statementPlayerId" => $statementPlayerId->getBytes()];
        $statement->execute($parameters);

        //grab statements from MySQL
        $statements = array();

        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        while (($row = $statement->fetch()) !== false) {
            try {
                $statement = new Statement($row["statementId"], $row["statementGameId"], $row["statementName"], $row["statementTeamNumber"],
                    $row["statementPlayed"], $row["statementLastModified"]);
                $statements[] = $statement;
            } catch (\Exception $exception) {
                //if row can't be converted rethrow it
                throw(new \PDOException($exception->getMessage(), 0, $exception));
            }
        }
        return ($statements);

    }

    /**
     * converts DateTimes and guids to string to serialize
     *
     * @return array converts DateTime to strings
     */
    public function jsonSerialize(): array
    {
        $fields = get_object_vars($this);
        if($this->statementId !== null) {
            $fields["statementId"] = $this->statementId->toString();
        }
        if($this->statementGameId !== null) {
            $fields["statementGameId"] = $this->statementGameId->toString();
        }
        if ($this->statementLastModified !== null) {
            $fields["statementLastModified"] = $this->statementLastModified->format("Y-m-d H:i:s");
        }
        return ($fields);
    }
}