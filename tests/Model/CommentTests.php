<?php

namespace UnitTests\my\Model;

use my\Model\Comment;
use my\Model\UUID;
use PHPUnit\Framework\TestCase;

class CommentTests extends TestCase
{
    public function testGetData(): void {
        $uuid = UUID::random();
        $authorUuid = UUID::random();
        $postUuid = UUID::random();
        $text = 'Text';
        $comment = new Comment(
            $uuid,
            $authorUuid,
            $postUuid,
            $text
        );

        $this->assertEquals($uuid, $comment->getUuid());
        $this->assertEquals($authorUuid, $comment->getAuthorUuid());
        $this->assertEquals($postUuid, $comment->getPostUuid());
        $this->assertEquals($text, $comment->getText());
    }
}