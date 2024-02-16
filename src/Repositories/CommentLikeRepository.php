<?php

namespace my\Repositories;

use PDO;
use PDOException;
use my\Exceptions\CommentLikeAlreadyExistsException;
use my\Exceptions\CommentLikeIncorrectDataException;
use my\Exceptions\CommentLikeNotFoundException;
use my\Model\CommentLike;
use my\Model\UUID;

class CommentLikeRepository implements CommentLikeRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) { }

    public function save(CommentLike $commentLike)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM comments WHERE uuid = :comment_uuid");
        $stmt->execute([':comment_uuid' => $commentLike->getCommentUuid()]);
        if ($stmt->fetchColumn() == 0) {
            throw new CommentLikeIncorrectDataException("Comment with UUID 
                {$commentLike->getCommentUuid()} not found");
        }

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE uuid = :user_uuid");
        $stmt->execute([':user_uuid' => $commentLike->getUserUuid()]);
        if ($stmt->fetchColumn() == 0) {
            throw new CommentLikeIncorrectDataException("User with UUID 
                {$commentLike->getUserUuid()} not found");
        }

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM comment_likes WHERE 
                                    comment_uuid = :comment_uuid AND user_uuid = :user_uuid");
        $stmt->execute([
            ':comment_uuid' => $commentLike->getCommentUuid(),
            ':user_uuid' => $commentLike->getUserUuid()
        ]);
        if ($stmt->fetchColumn() > 0) {
            throw new CommentLikeAlreadyExistsException("Like from user UUID 
                {$commentLike->getUserUuid()} to post UUID {$commentLike->getCommentUuid()} already exists");
        }

        $stmt = $this->pdo->prepare("INSERT INTO comment_likes (uuid, comment_uuid, user_uuid) 
                    VALUES (:uuid, :comment_uuid, :user_uuid)");

        try {
            $stmt->execute([
                ':uuid' => $commentLike->getUuid(),
                ':comment_uuid' => $commentLike->getCommentUuid(),
                ':user_uuid' => $commentLike->getUserUuid(),
            ]);
        } catch (PDOException $e) {
            throw new CommentLikeIncorrectDataException("Incorrect to save comment like: " .
                $e->getMessage());
        }
    }

    public function getByCommentUuid(UUID $commentUuid): array
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM comments WHERE uuid = :comment_uuid");
        $stmt->execute([':comment_uuid' => $commentUuid]);
        if ($stmt->fetchColumn() == 0) {
            throw new CommentLikeIncorrectDataException("Comment with UUID 
                {$commentUuid} not found");
        }

        $stmt = $this->pdo->prepare("SELECT * FROM comment_likes WHERE comment_uuid = :comment_uuid");

        try {
            $stmt->execute([':comment_uuid' => $commentUuid]);

            $likes = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $likes[] = new CommentLike(
                    new UUID($row['uuid']),
                    new UUID($row['comment_uuid']),
                    new UUID($row['user_uuid'])
                );
            }
        } catch (\PDOException) {
            throw new CommentLikeNotFoundException('Comment like not found');
        }

        return $likes;
    }
}