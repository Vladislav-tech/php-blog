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

    /**
     * Constructor to set connection with db.
     * @param string $dsn database:host=address;dbnmame=name is used to define some settings of connection.
     * @param string|null $username Name of db user.
     * @param string|null $password Password of user.
     */
    public function __construct(string $dsn, string $username = null, string $password = null)
    {

        try {
            $this->connection = new PDO($dsn, $username, $password);
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