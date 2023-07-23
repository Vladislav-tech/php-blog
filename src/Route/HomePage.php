<?php

declare(strict_types=1);

namespace Vladislav\PhpBlog\Route;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Environment;
use Vladislav\PhpBlog\Database;
use Vladislav\PhpBlog\LatestPosts;

/**
 * Class to represent home page
 */
class HomePage
{
    /**
     * @var LatestPosts
     */
    private LatestPosts $latestPosts;

    /**
     * @var Environment
     */
    private Environment $view;

    /**
     * @param LatestPosts $latestPosts
     * @param Environment $view
     */
    public function __construct(LatestPosts $latestPosts, Environment $view)
    {
        $this->latestPosts = $latestPosts;
        $this->view = $view;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function execute (Request $request, Response $response): Response
    {

        $posts = $this->latestPosts->get();

        $body = $this->view->render('index.twig', [
            'posts' => $posts
        ]);
        $response->getBody()->write($body);
        return $response;
    }
}