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
        $this->connection = $connection;
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