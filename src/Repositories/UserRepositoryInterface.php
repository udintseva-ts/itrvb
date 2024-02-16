<?php

namespace my\Repositories;

use my\Model\User;
use my\Model\UUID;

interface UserRepositoryInterface {
    public function save(User $user): void;
    public function get(UUID $uuid): User;
    public function getByUsername(string $username): User;
}