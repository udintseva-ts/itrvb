<?php

namespace my\Repositories;

use my\Model\Name;
use my\Model\User;
use my\Model\UUID;
use my\Exceptions\UserIncorrectDataException;
use my\Exceptions\UserNotFoundException;
use PDO;
use PDOException;

class UserRepository implements UserRepositoryInterface {
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function save(User $user): void {
        $stmt = $this->pdo->prepare("INSERT INTO users(uuid, username, first_name, last_name)
                                    VALUES (:uuid, :username, :first_name, :last_name),");

        try {
            $stmt->execute([
                ":uuid" => $user->getUuid(),
                ":username" => $user->getUsername(),
                ":first_name" => $user->getName()->getFirstName(),
                ":last_name" => $user->getName()->getLastName()
            ]);
        } catch (PDOException $e) {
            throw new UserIncorrectDataException("Ошибка при добавлении пользователя: " . $e->getMessage());
        }
    }

    public function getByUsername(string $username): User {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");

        try {
            $stmt->execute([
                ":username" => $username
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result)
                throw new UserNotFoundException("Cannot get user: $username");
        } catch (PDOException $e) {
            throw new UserNotFoundException("Ошибка при получении пользователя: " . $e->getMessage());
        }

        return new User(
            $result['uuid'],
            $result['username'],
            new Name(
                $result['first_name'],
                $result['last_name']
            )
        );
    }

    public function get(UUID $uuid): User {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE uuid = :uuid");

        try {
            $stmt->execute([
                ":uuid" => $uuid
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new UserIncorrectDataException("Ошибка при получении пользователя: " . $e->getMessage());
        }

        return new User(
            $result['uuid'],
            $result['username'],
            new Name(
                $result['first_name'],
                $result['last_name']
            )
        );
    }
}