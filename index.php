<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use DevCoder\DotEnv;
use DI\ContainerBuilder;


use Vladislav\PhpBlog\Route\HomePage;
use Vladislav\PhpBlog\Route\AboutPage;
use Vladislav\PhpBlog\Route\BlogPage;
use Vladislav\PhpBlog\Route\PostPage;

require __DIR__ . '/vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions('config/di.php');

(new DotEnv(__DIR__ . '/.env'))->load();

$container = $builder->build();

AppFactory::setContainer($container);

// Create app
$app = AppFactory::create();

$app->get('/', HomePage::class . ':execute');

$app->get('/about', AboutPage::class);

$app->get('/blog[/{page}]', BlogPage::class);

$app->get('/{url_key}', PostPage::class);

$app->run();
