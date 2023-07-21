<?php

declare(strict_types=1);

namespace Vladislav\PhpBlog;

use PDO;

/**
 * Class to set connection with MySQL db to connect with posts.
 */
class PostMapper
{
    /**
     * @var PDO
     */
    private PDO $connection;

    /**
     * PostMapper constructor to set connection with MySQL db.
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get post from MySQL db using urlKey.
     * @param string $urlKey urlKey to get a post from MySQL db.
     * @return array|null Array containing of selected post by urlKey or null(if wasn't found one).
     */
    public function getByUrlKey(string $urlKey): ?array
    {
        $statement = $this->connection->prepare('SELECT * FROM post WHERE url_key = :url_key');
        $statement->execute([
            'url_key' => $urlKey
        ]);

        $result = $statement->fetchAll();

        return array_shift($result);
    }
}