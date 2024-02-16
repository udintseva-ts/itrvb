<?php

namespace my\Model;

class User {
    public UUID $uuid;
    public string $username;
    public Name $name;

    public function getUuid(): UUID {
        return $this->uuid;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getName(): Name {
        return $this->name;
    }
}

?>