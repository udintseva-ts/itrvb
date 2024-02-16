<?php
namespace http\Actions\Users;

use my\Exceptions\UserNotFoundException;
use http\Actions\ActionInterface;
use http\ErrorResponse;
use http\SuccessfullResponse;
use http\Request;
use http\Response;
use my\Repositories\UserRepository;
use my\Exceptions\HttpException;

class FindByUsername implements ActionInterface
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function handle(Request $request): Response
    {
        try {
            $username = $request->query('username');
        } catch (HttpException $ex) {
            return new ErrorResponse($ex->getMessage());
        }

        try {
            $user = $this->userRepository->getByUsername($username);
        } catch (UserNotFoundException $ex) {
            return new ErrorResponse($ex->getMessage());
        }

        return new SuccessfullResponse([
            'username' => $user->getUsername(),
            'name' => (string)$user->getName()
        ]);
    }
}