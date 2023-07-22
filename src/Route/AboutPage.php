<?php

declare(strict_types=1);

namespace Vladislav\PhpBlog\Route;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Environment;

class AboutPage
{
    private Environment $view;

    public function __construct(Environment $view)
    {
        $this->view = $view;
    }
    public function __invoke(Request $request, Response $response)
    {
        $body = $this->view->render('about.twig', [
            'name' => 'Max'
        ]);
        $response->getBody()->write($body);
        return $response;
    }
}