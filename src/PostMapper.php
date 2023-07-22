<?php

declare(strict_types=1);

namespace Vladislav\PhpBlog;

use Exception;
use PDO;

/**
 * Class to set connection with MySQL db to connect with posts.
 */
class PostMapper
{
    /**
     * @var PDO Connection with MySQL db
     */
    private PDO $connection;

    /**
     * PostMapper constructor to set connection with MySQL db.
     * @param PDO $connection Connection with MySQL db.
     */
    public function __construct(PDO $connection)
    {
        // Set new connection with MySQL db
        $this->connection = $connection;
    }

    /**
     * Get post from MySQL db using urlKey.
     * @param string $urlKey urlKey to get a post from MySQL db.
     * @return array|null Array containing of selected post by urlKey or null(if wasn't found one).
     */
    public function getByUrlKey(string $urlKey): ?array
    {
        // SQL query
        $statement = $this->connection->prepare('SELECT * FROM post WHERE url_key = :url_key');

        // Execute sql query by placeholders
        $statement->execute([
            'url_key' => $urlKey
        ]);

        // All posts
        $result = $statement->fetchAll();

        // Get first post from db or null
        return array_shift($result);
    }

    /**
     *  Get all posts from MySQL db.
     * @param int $page = 1 Get page.
     * @param int $limit = 3 How many posts on the current page will be displayed.
     * @param string|null $sortType ='DESC' Type of sorting.
     * @return array|false All posts from MySQL db or false (if were not found any posts).
     * @throws Exception
     */
    public function getAllPosts(int $page = 1, int $limit = 3, ?string $sortType = 'DESC'): array|false
    {

        // Throw new Exception if $sortType is invalid
        if (!in_array(strtoupper($sortType), ['DESC', 'ASC'])) {
            throw new Exception('The sortType is not supported.');
        }

        // Offset start
        $start = ($page - 1) * $limit;

        // SQL query
        $statement = $this->connection->prepare(
            'SELECT * FROM post ORDER BY published_date ' . $sortType .
            ' LIMIT ' . $start . ',' . $limit
        );

        // Execute SQL query. There are no values because we did not use any placeholders in SQL query
        $statement->execute();

        // Return all posts or false
        return $statement->fetchAll();
    }


    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        // SQL query to count all posts in table
        $statement = $this->connection->prepare(
            'SELECT count(post_id) as total FROM post'
        );

        // Execute SQL query
        $statement->execute();

        // Return amount of posts or 0
        return (int)($statement->fetchColumn() ?? 0);
    }
}