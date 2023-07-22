<?php

namespace Vladislav\PhpBlog\Route;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Environment;
use Vladislav\PhpBlog\PostMapper;

class BlogPage
{

    private Environment $view;

    private PostMapper $postMapper;

    public function __construct(PostMapper $postMapper, Environment $view)
    {
        $this->view = $view;
        $this->postMapper = $postMapper;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {


        $page = isset($args['page']) ? (int)$args['page'] : 1;
        $limit = 2;

        $posts = $this->postMapper->getAllPosts($page, $limit, 'DESC');

        $totalCount = $this->postMapper->getTotalCount();

        $body = $this->view->render('blog.twig', [
            'posts' => $posts,
            'pagination' => [
                'current' => $page,
                'paging' => ceil($totalCount / $limit)
            ]
        ]);
        $response->getBody()->write($body);
        return $response;
    }
}