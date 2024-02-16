<?php

namespace UnitTests\my\Model;

use my\Exceptions\InvalidArgumentException;
use my\Model\UUID;
use PHPUnit\Framework\TestCase;

class UUIDTests extends TestCase
{
    public function testIncorrectUuid(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Не корректный UUID');

        $uuid = 'gg--------';
        $uuid = new UUID($uuid);
    }

    public function testToString(): void {
        $myUuid = 'f28262e5-df9c-4fd6-b38e-08421c645349';
        $uuid = new UUID($myUuid);

        $this->assertEquals($myUuid, $uuid);
    }

    public function testGenerateUuid(): void {
        $myUuid = UUID::random();
        $uuid = new UUID($myUuid);

        $this->assertEquals($myUuid, $uuid);
    }
}