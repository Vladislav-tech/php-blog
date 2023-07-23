<?php

declare(strict_types=1);

namespace Vladislav\PhpBlog\Route;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Environment;
use Vladislav\PhpBlog\PostMapper;

/**
 * Class to represent home page
 */
class PostPage
{
    /**
     * @var Environment
     */
    private Environment $view;

    /**
     * @var PostMapper
     */
    private PostMapper $postMapper;

    /**
     * @param PostMapper $postMapper
     * @param Environment $view
     */
    public function __construct(PostMapper $postMapper, Environment $view)
    {
        $this->view = $view;
        $this->postMapper = $postMapper;

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(Request $request, Response $response, array $args = []): Response
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