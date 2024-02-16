<?php

namespace my\Model;

class Post {
    public UUID $uuid;
    public UUID $author_uuid;
    public string $title;
    public string $text;

    public function getUuid(): UUID {
        return $this->uuid;
    }

    public function getAuthorUuid(): UUID {
        return $this->author_uuid;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getText(): string {
        return $this->text;
    }
}

?>