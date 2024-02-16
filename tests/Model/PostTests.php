<?php

namespace UnitTests\my\Model;

use my\Model\Post;
use my\Model\UUID;
use PHPUnit\Framework\TestCase;

class ArticleTests extends TestCase
{
    public function testGetData(): void {
        $uuid = UUID::random();
        $authorUuid = UUID::random();
        $title = 'Title1';
        $text = 'Text';
        $post = new Post(
            $uuid,
            $authorUuid,
            $title,
            $text
        );

        $this->assertEquals($uuid, $post->getUuid());
        $this->assertEquals($authorUuid, $post->getAuthorUuid());
        $this->assertEquals($title, $post->getTitle());
        $this->assertEquals($text, $post->getText());
    }
}