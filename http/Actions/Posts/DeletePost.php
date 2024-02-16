<?php

namespace http\Actions\Posts;

use http\Actions\ActionInterface;
use http\ErrorResponse;
use http\Request;
use http\Response;
use http\SuccessfullResponse;
use my\Repositories\PostRepository;
use my\Exceptions\HttpException;

class DeletePost implements ActionInterface
{
    public function __construct(
        private PostRepository $postRepository
    ) { }

    public function handle(Request $request): Response
    {
        try {
            $uuid = $request->query('uuid');
            $this->postRepository->delete($uuid);
            return new SuccessfullResponse(['message' => 'Post deleted successfully']);
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
    }
}