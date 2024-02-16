<?php
namespace my\Repositories;

use my\Model\Comment;

interface CommentsRepositoryInterface {
    public function get(string $uuid): Comment;
    public function save(Comment $comment): void;
}

?>