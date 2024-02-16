<?php

namespace UnitTests\my\Repositories;

use my\Exceptions\PostNotFoundException;
use my\Model\Post;
use my\Model\UUID;
use my\Repositories\PostRepository;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class ArticleRepositoryTests extends TestCase
{
    private $pdoMock;
    private $stmtMock;
    private $repo;

    protected function setUp(): void {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->repo = new PostRepository($this->pdoMock);
    }

    public function testSavePost(): void {
        $uuid = UUID::random();
        $authorUuid = UUID::random();
        $post = new Post($uuid, $authorUuid, 'Test Title', 'Test Text');

        $expectedParams = [
            ':uuid' => $uuid,
            ':author_uuid' => $authorUuid,
            ':title' => 'Test Title',
            ':text' => 'Test Text'
        ];

        $this->pdoMock->method('prepare')
            ->willReturn($this->stmtMock);
        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($expectedParams));

        $this->repo->save($post);
    }

    public function testFindPostByUuid(): void {
        $uuid = UUID::random();
        $authorUuid = UUID::random();

        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetch')->willReturn([
            'uuid' => $uuid,
            'author_uuid' => $authorUuid,
            'title' => 'Test Title',
            'text' => 'Test Text'
        ]);

        $post = $this->repo->get($uuid);

        $this->assertNotNull($post);
        $this->assertEquals($uuid, $post->getUuid());
    }

    public function testThrowsExceptionIfPostNotFound(): void {
        $nonExistentUuid = UUID::random();

        $this->expectException(PostNotFoundException::class);
        $this->expectExceptionMessage("Статья с UUID $nonExistentUuid не найдена");

        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetch')->willReturn(false);

        $this->repo->get($nonExistentUuid);
    }
}