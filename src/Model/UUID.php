<?php
namespace my\Model;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class UUID {
    public function __construct(
        private string $uuid
    ) {
        if (!RamseyUuid::isValid($this->uuid))
            throw new InvalidArgumentException('Не корректный UUID');
    }

    public function __toString(): string {
        return $this->uuid;
    }

    public static function random(): self {
        return new self(RamseyUuid::uuid4()->toString());
    }
}