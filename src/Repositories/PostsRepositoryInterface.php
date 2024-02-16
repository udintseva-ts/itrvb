<?php

namespace my\Repositories;

use my\Model\Post;

interface PostsRepositoryInterface {
    public function get(string $uuid): Post;
    public function save(Post $post): void;
}
?>