<?php

namespace Vladislav\PhpBlog\Route;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Environment;
use Vladislav\PhpBlog\PostMapper;

class PostPage
{
    private Environment $view;

    private PostMapper $postMapper;
    public function __construct(PostMapper $postMapper, Environment $view)
    {
        $this->view = $view;
        $this->postMapper = $postMapper;

    }

    public function __invoke(Request $request, Response $response, array $args = [])
    {
        $post = $this->postMapper->getByUrlKey((string) $args['url_key']);

        if (empty($post)) {
            $body = $this->view->render('not-found.twig');
        }   else {
            $body = $this->view->render('post.twig', [
                'post' => $post
            ]);
        }


        $response->getBody()->write($body);
        return $response;
    }
}