<?php

namespace UnitTests\my\Model;

use my\Model\Name;
use PHPUnit\Framework\TestCase;

class NameTests extends TestCase
{
    private $name;
    function setUp(): void {
        $this->name =  new Name(
            'fN',
            'lN'
        );
    }

    public function testGetData(): void {
        $firstName = 'fN';
        $lastName = 'lN';

        $this->assertEquals($firstName, $this->name->getFirstName());
        $this->assertEquals($lastName, $this->name->getLastName());
    }

    public function testToString(): void {
        $fullName = 'fN lN';
        $this->assertEquals($fullName, $this->name);
    }
}