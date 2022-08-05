<?php
namespace JOHNTHEDEV\Game;

require_once(dirname(__DIR__) . "/Classes/autoload.php");
require_once(dirname(__DIR__, 1) . "/vendor/autoload.php");

use JetBrains\PhpStorm\ArrayShape;
use Ramsey\Uuid\UuidInterface;


/**
 * Class vote will store the votes
 * @package JohnTheDev
 *
 * Description: This class will hold the vote info.
 *
 * @author John Johnson-Rodgers <john@johnthe.dev>
 */
class Vote implements \JsonSerializable {
    use ValidateUuid;
    use ValidateDate;

    /**
     * id for vote; Primary key - Not null, uuid
     * @var UuidInterface $voteId
     */
    private UuidInterface $voteId;

    /**
     * Uuid for the Statement this vote is for - not null, uuid
     * @var UuidInterface $voteStatementId
     */
    private UuidInterface $voteStatementId;

    /**
     * Uuid for the Player this vote is for - not null, uuid
     * @var UuidInterface $votePlayerId
     */
    private UuidInterface $votePlayerId;

    /**
     * whether the vote has been used this round - not null, boolean
     * @var bool $voteTrue
     */
    private bool $voteTrue;

    /***
     *       _____    ____    _   _    _____   _______   _____    _    _    _____   _______    ____    _____
     *      / ____|  / __ \  | \ | |  / ____| |__   __| |  __ \  | |  | |  / ____| |__   __|  / __ \  |  __ \
     *     | |      | |  | | |  \| | | (___      | |    | |__) | | |  | | | |         | |    | |  | | | |__) |
     *     | |      | |  | | | . ` |  \___ \     | |    |  _  /  | |  | | | |         | |    | |  | | |  _  /
     *     | |____  | |__| | | |\  |  ____) |    | |    | | \ \  | |__| | | |____     | |    | |__| | | | \ \
     *      \_____|  \____/  |_| \_| |_____/     |_|    |_|  \_\  \____/   \_____|    |_|     \____/  |_|  \_\
     */

    /**
     * Vote constructor.
     * @param string $voteId
     * @param string $voteStatementId
     * @param string $votePlayerId
     * @param bool $voteTrue
     * @throws \InvalidArgumentException | \RangeException | \TypeError | \Exception if setters do not work
     */
    public function __construct(string $voteId, bool $voteTrue, string $voteStatementId, string $votePlayerId)
    {
        try {
            $this->setVoteId($voteId);
            $this->setVoteTrue($voteTrue);
            $this->setVoteStatementId($voteStatementId);
            $this->setVotePlayerId($votePlayerId);
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
     * Accessor for voteId, Not Null
     * Primary Key
     *
     * @return UuidInterface
     */
    public function getVoteId(): UuidInterface
    {
        return ($this->voteId);
    }

    /**
     *Mutator Method for voteId, Not Null
     *Primary Key
     *
     * @param UuidInterface|string $voteId
     * @throws \Exception if $voteId is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setVoteId(UuidInterface|string $voteId): void
    {
        try {
            //makes sure uuid is valid
            $uuid = self::validateUuid($voteId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            //throws exception if the uuid is invalid.
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Vote Class Exception: setVoteId: " . $exception->getMessage(), 0, $exception));
        }
        $this->voteId = $uuid;
    }

    /**
     * Accessor for voteStatementId, Not Null
     * Primary Key
     *
     * @return UuidInterface
     */
    public function getVoteStatementId(): UuidInterface
    {
        return ($this->voteStatementId);
    }

    /**
     *Mutator Method for voteStatementId, Not Null
     *Primary Key
     *
     * @param UuidInterface|string $voteStatementId
     * @throws \Exception if $voteStatementId is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setVoteStatementId(UuidInterface|string $voteStatementId): void
    {
        try {
            //makes sure uuid is valid
            $uuid = self::validateUuid($voteStatementId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            //throws exception if the uuid is invalid.
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Vote Class Exception: setVoteStatementId: " . $exception->getMessage(), 0, $exception));
        }
        $this->voteStatementId = $uuid;
    }

    /**
     * Accessor for votePlayerId, Not Null
     * Primary Key
     *
     * @return UuidInterface
     */
    public function getVotePlayerId(): UuidInterface
    {
        return ($this->votePlayerId);
    }

    /**
     *Mutator Method for votePlayerId, Not Null
     *Primary Key
     *
     * @param UuidInterface|string $votePlayerId
     * @throws \Exception if $votePlayerId is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setVotePlayerId(UuidInterface|string $votePlayerId): void
    {
        try {
            //makes sure uuid is valid
            $uuid = self::validateUuid($votePlayerId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            //throws exception if the uuid is invalid.
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Vote Class Exception: setVotePlayerId: " . $exception->getMessage(), 0, $exception));
        }
        $this->votePlayerId = $uuid;
    }

    /**
     * Accessor Method for voteTrue
     *
     * @return bool
     */
    public function getVoteTrue(): bool
    {
        return ($this->voteTrue);
    }

    /**
     * Mutator Method for voteTrue
     *
     * @param bool $newVoteTrue
     * @throws \Exception if $newVoteTrue is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setVoteTrue(bool $newVoteTrue): void
    {
        //filter out invalid input
        $newVoteTrue = filter_var($newVoteTrue, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        if($newVoteTrue==null){
            throw new \InvalidArgumentException("Vote Class Exception: Vote True is invalid");
        }
        $this->voteTrue = $newVoteTrue;
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
     * Inserts Vote into MySQL
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException if MySQL errors occur
     * @throws \TypeError if $PDO is not a PDO connection object
     */
    public function insert(\PDO $pdo): void
    {
        //create query template
        $query = "INSERT INTO vote (voteId,  voteStatementId, votePlayerId, voteTrue) 
                    VALUES(:voteId,  :voteStatementId, :votePlayerId, :voteTrue)";
        $statement = $pdo->prepare($query);
        //create parameters for query
        $parameters = $this->getParameters();
        $statement->execute($parameters);
    }
//
    /**
     * UPDATE
     * updates Vote in MySQL database
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException when MySQL related error occurs
     * @throws \TypeError if $pdo is not pdo connection object
     */
    public function update(\PDO $pdo): void
    {
        //create query template
        $query = "UPDATE vote SET voteId = :voteId, voteStatementId = :voteStatementId, votePlayerId = :votePlayerId, 
                voteTrue = :voteTrue WHERE voteId = :voteId";
        $statement = $pdo->prepare($query);
        // set parameters to execute query
        $parameters = $this->getParameters();
        $statement->execute($parameters);
    }
//
    /**
     * DELETE
     * deletes Vote from MySQL database
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException when mysql related errors occur
     * @throws \TypeError when $pdo is not a PDO object
     */
    public function delete(\PDO $pdo): void
    {
        //create query template
        $query = "DELETE FROM vote WHERE voteId = :voteId";
        $statement = $pdo->prepare($query);
        //set parameters to execute query
        $parameters = ["voteId" => $this->voteId->getBytes()];
        $statement->execute($parameters);
    }

    #[ArrayShape(["voteId" => "string",  "voteStatementId" => "string", "votePlayerId" => "string", "voteTrue" => "bool"])]
    private function getParameters(): array {
        return [
            "voteId" => $this->voteId->getBytes(),
            "voteStatementId" => $this->voteStatementId->getBytes(),
            "votePlayerId" => $this->votePlayerId->getBytes(),
            "voteTrue" => $this->voteTrue
        ];
    }
//

    /**
     * get vote by voteId
     *
     * @param \PDO $pdo
     * @param string $voteId
     * @return Vote|null
     * @throws \PDOException when mysql related errors occur
     * @throws \TypeError when variable doesn't follow typehints
     * @throws \Exception
     */
    public static function getVoteByVoteId(\PDO $pdo, string $voteId): ?Vote
    {
        //trim and filter out invalid input
        try {
            $voteId = self::validateUuid($voteId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Vote Class Exception: getVoteByVoteId: " . $exception->getMessage(), 0, $exception));
        }

        //create query template
        $query = "SELECT voteId,  voteStatementId, votePlayerId, voteTrue
                    FROM vote WHERE voteId = :voteId";
        $statement = $pdo->prepare($query);

        //set parameters to execute
        $parameters = ["voteId" => $voteId->getBytes()];
        $statement->execute($parameters);

        //grab vote from MySQL
        try {
            $vote = null;
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            $row = $statement->fetch();
            if ($row !== false) {
                $vote = new Vote($row["voteId"],  $row["voteStatementId"], $row["votePlayerId"], $row["voteTrue"]);
            }
        } catch (\Exception $exception) {
            //if row can't be converted rethrow it
            throw(new \PDOException($exception->getMessage(), 0, $exception));
        }
        return ($vote);

    }

    /**
     * get vote by voteId
     *
     * @param \PDO $pdo
     * @param string $statementId
     * @return array
     * @throws \PDOException when mysql related errors occur
     * @throws \TypeError when variable doesn't follow typehints
     * @throws \Exception
     */
    public static function getVotesByStatementId(\PDO $pdo, string $statementId): array
    {
        //trim and filter out invalid input
        try {
            $statementId = self::validateUuid($statementId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Vote Class Exception: getVoteByVoteId: " . $exception->getMessage(), 0, $exception));
        }

        //create query template
        $query = "SELECT voteId,  voteStatementId, votePlayerId, voteTrue
                    FROM vote WHERE voteStatementId = :voteStatementId";
        $statement = $pdo->prepare($query);

        //set parameters to execute
        $parameters = ["statementId" => $statementId->getBytes()];
        $statement->execute($parameters);

        $votes = array();
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        while(($row = $statement->fetch())!==false){
            try {
                $vote = new Vote($row["voteId"], $row["voteStatementId"], $row["votePlayerId"], $row["voteTrue"]);
                $votes[] = $vote;
            } catch (\Exception $exception) {
                //if row can't be converted rethrow it
                throw(new \PDOException($exception->getMessage(), 0, $exception));
            }
        }
        //grab vote from MySQL

        return ($votes);

    }

    /**
     * @param \PDO $pdo
     * @param string $statementId
     * @return bool
     * @throws \Exception
     */
    public static function getVoteResultsByStatementId(\PDO $pdo, string $statementId): bool
    {
        //trim and filter out invalid input
        try {
            $statementId = self::validateUuid($statementId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Vote Class Exception: getVoteResultsByStatementId: " . $exception->getMessage(), 0, $exception));
        }

        //create query template
        $query = "SELECT statementTrue FROM statement WHERE statementId = :statementId";
        $statement = $pdo->prepare($query);

        //set parameters to execute
        $parameters = ["statementId" => $statementId->getBytes()];
        $statement->execute($parameters);

        //grab vote from MySQL
        $statementIsTrue=false;
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $statement->fetch();
        if ($row !== false) {
            $statementIsTrue = $row["statementTrue"];
        }


        //create query template
        $query = "SELECT COUNT(DISTINCT voteId) FROM vote WHERE voteTrue = TRUE";
        $statement = $pdo->prepare($query);

        //set parameters to execute
        $parameters = ["statementId" => $statementId->getBytes()];
        $statement->execute($parameters);

        //grab vote from MySQL
        $trueVotes=0;
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $statement->fetch();
        if ($row !== false) {
            $trueVotes = $row["statementTrue"];
        }
        //create query template
        $query = "SELECT COUNT(DISTINCT voteId) FROM vote WHERE voteTrue = FALSE";
        $statement = $pdo->prepare($query);

        //set parameters to execute
        $parameters = ["statementId" => $statementId->getBytes()];
        $statement->execute($parameters);

        //grab vote from MySQL
        $falseVotes=0;
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $statement->fetch();
        if ($row !== false) {
            $falseVotes = $row["statementTrue"];
        }
        if(($falseVotes > $trueVotes) && !$statementIsTrue){
            return true;
        }elseif (($falseVotes < $trueVotes) && $statementIsTrue){
            return true;
        } else {
            //all ties lose
            return false;
        }
    }

    /**
     * converts DateTimes and guids to string to serialize
     *
     * @return array converts DateTime to strings
     */
    public function jsonSerialize(): array
    {
        $fields = get_object_vars($this);
        if($this->voteId !== null) {
            $fields["voteId"] = $this->voteId->toString();
        }
        if($this->votePlayerId !== null) {
            $fields["votePlayerId"] = $this->votePlayerId->toString();
        }
        return ($fields);
    }
}