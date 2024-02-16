<?php

namespace my\Commands;

use my\Exceptions\CommandException;
use my\Exceptions\UserNotFoundException;
use my\Repositories\UserRepositoryInterface;
use my\Model\UUID;
use my\Model\User;
use my\Model\Name;

class CreateUserCommand {
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function userExist(string $username): bool {
        try {
            $this->userRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }

        return true;
    }

    public function handle(Arguments $arguments): void {
        $username = $arguments->get('username');

        if ($this->userExist($username)) {
            throw new CommandException(
                "User already exists: $username"
            );
        }

        $this->userRepository->save(new User(
            UUID::random(),
            $username,
            new Name(
                $arguments->get('first_name'),
                $arguments->get('last_name')
            )
        ));
    }
}