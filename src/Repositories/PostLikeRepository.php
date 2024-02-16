<?php

namespace my\Repositories;

use PDO;
use PDOException;
use my\Exceptions\PostIncorrectDataException;
use my\Exceptions\PostLikeAlreadyExistsException;
use my\Exceptions\PostLikeNotFoundException;
use my\Model\PostLike;
use my\Model\UUID;

class PostLikeRepository implements PostLikeRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) { }

    public function save(PostLike $postLike)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM posts WHERE uuid = :post_uuid");
        $stmt->execute([':post_uuid' => $postLike->getPostUuid()]);
        if ($stmt->fetchColumn() == 0) {
            throw new PostIncorrectDataException("Post with UUID 
                {$postLike->getPostUuid()} not found");
        }

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE uuid = :user_uuid");
        $stmt->execute([':user_uuid' => $postLike->getUserUuid()]);
        if ($stmt->fetchColumn() == 0) {
            throw new PostIncorrectDataException("User with UUID 
                {$postLike->getUserUuid()} not found");
        }

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM post_likes WHERE 
                                    post_uuid = :post_uuid AND user_uuid = :user_uuid");
        $stmt->execute([
            ':post_uuid' => $postLike->getPostUuid(),
            ':user_uuid' => $postLike->getUserUuid()
        ]);
        if ($stmt->fetchColumn() > 0) {
            throw new PostLikeAlreadyExistsException("Like from user UUID 
                {$postLike->getUserUuid()} to post UUID {$postLike->getPostUuid()} already exists");
        }

        $stmt = $this->pdo->prepare("INSERT INTO post_likes (uuid, post_uuid, user_uuid) 
                    VALUES (:uuid, :post_uuid, :user_uuid)");

        try {
            $stmt->execute([
                ':uuid' => $postLike->getUuid(),
                ':post_uuid' => $postLike->getPostUuid(),
                ':user_uuid' => $postLike->getUserUuid(),
            ]);
        } catch (PDOException $e) {
            throw new PostIncorrectDataException("Incorrect to save comment like: " .
                $e->getMessage());
        }
    }

    public function getByPostUuid(UUID $postUuid): array
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM posts WHERE uuid = :post_uuid");
        $stmt->execute([':post_uuid' => $postUuid]);
        if ($stmt->fetchColumn() == 0) {
            throw new PostLikeNotFoundException("Post with UUID 
                {$postUuid} not found");
        }

        $stmt = $this->pdo->prepare("SELECT * FROM post_likes WHERE comment_uuid = :comment_uuid");

        try {
            $stmt->execute([':post_uuid' => $postUuid]);

            $likes = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $likes[] = new PostLike(
                    new UUID($row['uuid']),
                    new UUID($row['post_uuid']),
                    new UUID($row['user_uuid'])
                );
            }
        } catch (\PDOException) {
            throw new PostLikeNotFoundException('Comment like not found');
        }

        return $likes;
    }
}