<?php

namespace UnitTests\my\Commands;

use my\Commands\Arguments;
use my\Commands\CreateUserCommand;
use my\Exceptions\CommandException;
use my\Exceptions\UserNotFoundException;
use my\Model\Name;
use my\Model\User;
use my\Model\UUID;
use my\Repositories\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateUserCommandTests extends TestCase
{
    private $userRepository;
    private $createUserCommand;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->createUserCommand = new CreateUserCommand($this->userRepository);
    }

    public function testHandleCreatesUserWhenNotExists(): void
    {
        $this->userRepository
            ->method('getByUsername')
            ->will($this->throwException(new UserNotFoundException()));

        $arguments = $this->createMock(Arguments::class);
        $arguments->method('get')
            ->willReturnMap([
                ['username', 'testuser'],
                ['first_name', 'Test'],
                ['last_name', 'User']
            ]);

        $this->userRepository
            ->expects($this->once())
            ->method('save');

        $this->createUserCommand->handle($arguments);

        $this->assertTrue(true);
    }

    public function testHandleThrowsExceptionWhenUserExists(): void
    {
        $this->expectException(CommandException::class);
        $this->expectExceptionMessage("User already exists: testuser");

        $username = 'testuser';
        $uuid = UUID::random();
        $this->userRepository
            ->method('getByUsername')
            ->willReturn(new User(
                $uuid,
                $username,
                new Name(
                    "firstName",
                    "lastName"
                )
            ));

        $arguments = $this->createMock(Arguments::class);
        $arguments->method('get')
            ->willReturn($username);

        $this->createUserCommand->handle($arguments);
    }
}