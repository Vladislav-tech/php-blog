<?php

declare(strict_types=1);

namespace Vladislav\PhpBlog\Route;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Environment;

/**
 * Class to represent about page
 */
class AboutPage
{
    /**
     * @var Environment
     */
    private Environment $view;

    /**
     * @param Environment $view
     */
    public function __construct(Environment $view)
    {
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
    public function __invoke(Request $request, Response $response)
    {
        $body = $this->view->render('about.twig');
        $response->getBody()->write($body);
        return $response;
    }
}