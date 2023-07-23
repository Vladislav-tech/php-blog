<?php


namespace Vladislav\PhpBlog;
use PDO;
use PDOException;

/**
 * Class to connect with db
 */
class Database
{
    /**
     * Connection to db.
     * @var PDO
     */
    private PDO $connection;


    public function __construct(PDO $connection)
    {

        try {
            $this->connection = $connection;
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        }   catch(PDOException $exception) {
            print_r('Database error: '. $exception->getMessage());
            die();
        }
    }

    /**
     * Function to get the current connection with db.
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}