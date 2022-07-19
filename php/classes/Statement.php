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
class Statement implements \JsonSerializable {
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
     * current statement - nullable, uuid
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
     * @param string $messageId
     * @param string $messageContent
     * @param $messageDate \DateTime|string
     * @throws \InvalidArgumentException | \RangeException | \TypeError | \Exception if setters do not work
     */
    public function __construct(string $messageId, string $messageContent, string|\DateTime $messageDate,)
    {
        try {
            $this->setMessageId($messageId);
            $this->setMessageContent($messageContent);
            $this->setMessageDate($messageDate);
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
     * Accessor for messageId, Not Null
     * Primary Key
     *
     * @return UuidInterface
     */
    public function getMessageId(): UuidInterface
    {
        return ($this->messageId);
    }

    /**
     *Mutator Method for messageId, Not Null
     *Primary Key
     *
     * @param UuidInterface|string $messageId
     * @throws \Exception if $messageId is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setMessageId(UuidInterface|string $messageId): void
    {
        try {
            //makes sure uuid is valid
            $uuid = self::validateUuid($messageId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            //throws exception if the uuid is invalid.
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Message Class Exception: setMessageId: " . $exception->getMessage(), 0, $exception));
        }
        $this->messageId = $uuid;
    }

    /**
     * Accessor Method for messageContent
     *
     * @return string
     */
    public function getMessageContent(): string
    {
        return ($this->messageContent);
    }

    /**
     * Mutator Method for messageContent
     *
     * @param string $newMessageContent
     * @throws \Exception if $newMessageContent is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setMessageContent(string $newMessageContent): void
    {
        //trim and filter out invalid input
        $newMessageContent = trim($newMessageContent);
        $newMessageContent = filter_var($newMessageContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        //checks if string length is appropriate
        if (strlen($newMessageContent) > 60000) {
            throw (new \RangeException("Message Class Exception: MessageContent is too long"));
        }
        $this->messageContent = $newMessageContent;
    }

    /**
     * Accessor Method for messageDate
     *
     * @return \DateTime|string
     */
    public function getMessageDate(): string|\DateTime
    {
        return ($this->messageDate);
    }

    /**
     * Mutator Method for messageDate
     *
     * @param string|null $newMessageDate
     * @throws \Exception if $newMessageDate is an invalid argument, out of range, has a type error, or has another exception.
     */
    public function setMessageDate(null|string|\DateTime $newMessageDate): void
    {
        //checks if $newMessageDate is null, if so set to current DateTime
        if($newMessageDate === null){
            $this->messageDate = new \DateTime();
        } else {
            try {
                $newMessageDate = self::validateDateTime($newMessageDate);
            } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
                $exceptionType = get_class($exception);
                throw(new $exceptionType("Message Class Exception: setMessageDate: " . $exception->getMessage(), 0, $exception));
            }
            $this->messageDate = $newMessageDate;
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
        $query = "INSERT INTO message(messageId, messageContent, messageDate) VALUES(:messageId, :messageContent, :messageDate)";
        $statement = $pdo->prepare($query);
        //create parameters for query
        $parameters = [
            "messageId" => $this->messageId->getBytes(),
            "messageContent" => $this->messageContent,
            "messageDate" => $this->messageDate->format("Y-m-d H:i:s")
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
        $query = "UPDATE message SET messageContent=:messageContent, messageDate=:messageDate WHERE messageId = :messageId";
        $statement = $pdo->prepare($query);
        // set parameters to execute query
        $parameters = [
            "messageId" => $this->messageId->getBytes(),
            "messageContent" => $this->messageContent,
            "messageDate" => $this->messageDate->format("Y-m-d H:i:s")
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
        $query = "DELETE FROM message WHERE messageId = :messageId";
        $statement = $pdo->prepare($query);
        //set parameters to execute query
        $parameters = ["messageId" => $this->messageId->getBytes()];
        $statement->execute($parameters);
    }
//

    /**
     * get message by messageId
     *
     * @param \PDO $pdo
     * @param string $messageId
     * @return Message|null
     * @throws \PDOException when mysql related errors occur
     * @throws \TypeError when variable doesn't follow typehints
     * @throws \Exception
     */
    public static function getMessageByMessageId(\PDO $pdo, string $messageId): ?Message
    {
        //trim and filter out invalid input
        try {
            $messageId = self::validateUuid($messageId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            $exceptionType = get_class($exception);
            throw(new $exceptionType("Message Class Exception: getMessageByMessageId: " . $exception->getMessage(), 0, $exception));
        }

        //create query template
        $query = "SELECT messageId, messageContent, messageDate FROM message WHERE messageId = :messageId";
        $statement = $pdo->prepare($query);

        //set parameters to execute
        $parameters = ["messageId" => $messageId->getBytes()];
        $statement->execute($parameters);

        //grab message from MySQL
        try {
            $message = null;
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            $row = $statement->fetch();
            if ($row !== false) {
                $message = new Message($row["messageId"], $row["messageContent"], $row["messageDate"]);
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
        $query = "SELECT messageId, messageContent, messageDate FROM message ORDER BY messageDate DESC";
        $statement = $pdo->prepare($query);

        //set parameters to execute
        $parameters = [];
        $statement->execute($parameters);

        //grab message from MySQL
        $messages = array();
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        while (($row = $statement->fetch()) !== false) {
            try {
                $message = new Message($row["messageId"], $row["messageContent"], $row["messageDate"]);
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
        if($this->messageId !== null) {
            $fields["messageId"] = $this->messageId->toString();
        }
        if ($this->messageDate !== null) {
            $fields["messageDate"] = $this->messageDate->format("Y-m-d H:i:s");
        }
        return ($fields);
    }
}