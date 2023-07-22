<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Twig\Environment;
use DevCoder\DotEnv;
use Vladislav\PhpBlog\PostMapper;
use Vladislav\PhpBlog\LatestPosts;
use DI\ContainerBuilder;
use Vladislav\PhpBlog\Database;

use Vladislav\PhpBlog\Route\HomePage;
use Vladislav\PhpBlog\Route\AboutPage;


require __DIR__ . '/vendor/autoload.php';


$builder = new ContainerBuilder();
$builder->addDefinitions('config/di.php');

(new DotEnv(__DIR__ . '/.env'))->load();

$container = $builder->build();
$view = $container->get(Environment::class);

$connection = $container->get(Database::class)->getConnection();

AppFactory::setContainer($container);

$postMapper = new PostMapper($connection);

// Create app
$app = AppFactory::create();

$app->get('/', HomePage::class . ':execute');

$app->get('/about', AboutPage::class);

$app->get('/blog[/{page}]', function (Request $request, Response $response, array $args) use ($view, $connection) {
    $postMapper = new PostMapper($connection);

    $page = isset($args['page']) ? (int)$args['page'] : 1;
    $limit = 2;

    $posts = $postMapper->getAllPosts($page, $limit, 'DESC');

    $totalCount = $postMapper->getTotalCount();

    $body = $view->render('blog.twig', [
        'posts' => $posts,
        'pagination' => [
            'current' => $page,
            'paging' => ceil($totalCount / $limit)
        ]
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/{url_key}', function (Request $request, Response $response, array $args) use ($view, $connection) {
    $postMapper = new PostMapper($connection);
    $post = $postMapper->getByUrlKey((string) $args['url_key']);

    if (empty($post)) {
        $body = $view->render('not-found.twig');
    }   else {
        $body = $view->render('post.twig', [
            'post' => $post
        ]);
    }


    $response->getBody()->write($body);
    return $response;
});
$app->run();
