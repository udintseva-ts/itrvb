<?php

namespace UnitTests\Http\Action\Users;

use http\Actions\Users\FindByUsername;
use http\ErrorResponse;
use http\Request;
use http\SuccessfullResponse;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use my\Model\UUID;
use my\Repositories\UserRepository;

class FindByUsernameTest extends TestCase
{
    private $pdoMock;
    private $stmtMock;
    private UserRepository $repo;

    protected function setUp(): void {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->repo = new UserRepository($this->pdoMock);
    }

    public function testItReturnErrorIfParamUserNotFound(): void
    {
        $request = new Request([], []);
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('fetch')->willReturn(false);

        $action = new FindByUsername($this->repo);

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString('{"succuess":false,"reason":"Incorrect param for query"}');
        $response->send();
    }

    public function testItReturnErrorIfUserNotFound(): void
    {
        $request = new Request(['username' => 'Ivan'], []);
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('fetch')->willReturn(false);

        $action = new FindByUsername($this->repo);

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString('{"succuess":false,"reason":"Cannot get user: Ivan"}');
        $response->send();
    }

    public function testItReturnUserByName(): void
    {
        $uuid = UUID::random();

        $mockUserData = [
            'uuid' => $uuid,
            'username' => 'ivan',
            'first_name' => 'Ivan',
            'last_name' => 'Ivanov',
        ];

        $request = new Request(['username' => 'Ivan'], []);
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('fetch')->willReturn($mockUserData);

        $action = new FindByUsername($this->repo);
        $response = $action->handle($request);

        $this->assertInstanceOf(SuccessfullResponse::class, $response);
        $this->expectOutputString('{"succuess":true,"data":{"username":"ivan","name":"Ivan Ivanov"}}');

        $response->send();
    }
}