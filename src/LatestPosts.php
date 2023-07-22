<?php

declare(strict_types=1);

namespace Vladislav\PhpBlog;

use PDO;
use Vladislav\PhpBlog\Database;


/**
 * Class to set connection with MySQL db and method to get latest posts.
 */
class LatestPosts
{
    /**
     * @var Database Connection with MySQL db
     */
    private Database $database;

    /**
     * LatestPosts constructor to set connection with MySQL db.
     * @param Database $database Connection with MySQL db.
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Get posts from MySQL db using $limit.
     * @param int $limit=5 Amount of posts to get it.
     * @return array Array of posts.
     */
    public function get(int $limit = 5): array
    {
        // Get posts ordered by published_date in DESC order
        $statement = $this->database->getConnection()->prepare(
            'SELECT * FROM post ORDER BY published_date DESC LIMIT '. $limit
        );

        // Execute SQL query
        $statement->execute();

        // Return all posts
        return $statement->fetchAll();
    }
}