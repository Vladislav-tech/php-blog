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
//use Vladislav\PhpBlog\Slim\TwigMiddleware;

require __DIR__ . '/vendor/autoload.php';

//$loader = new FilesystemLoader('templates');
//$view = new Environment($loader);

$builder = new ContainerBuilder();
$builder->addDefinitions('config/di.php');

(new DotEnv(__DIR__ . '/.env'))->load();

$container = $builder->build();
$view = $container->get(Environment::class);

$connection = $container->get(Database::class)->getConnection();

AppFactory::setContainer($container);

//$config = include 'config/database.php';
//$dsn = $config['dsn'];
//$username = $config['username'];
//$password = $config['password'];
//
//try {
//    $connection = new PDO($dsn, $username, $password);
//    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
//
//}   catch(PDOException $exception) {
//    print_r('Database error: '. $exception->getMessage());
//    die();
//}

$postMapper = new PostMapper($connection);

// Create app
$app = AppFactory::create();
//$app->add(new TwigMiddleware($view));

$app->get('/', function (Request $request, Response $response) use ($view, $connection) {
    $latestPosts = new LatestPosts($connection);
    $posts = $latestPosts->get();

    $body = $view->render('index.twig', [
        'posts' => $posts
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/about', function (Request $request, Response $response) use ($view) {
    $body = $view->render('about.twig', [
        'name' => 'Max'
    ]);
    $response->getBody()->write($body);
    return $response;
});

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
