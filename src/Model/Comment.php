<?php

namespace my\Model;

class Comment {
    public UUID $uuid;
    public UUID $post_uuid;
    public UUID $author_uuid;
    public string $text;

    public function getUuid(): UUID {
        return $this->uuid;
    }

    public function getAuthorUuid(): UUID {
        return $this->author_uuid;
    }

    public function getPostUuid(): UUID {
        return $this->post_uuid;
    }

    public function getText(): string {
        return $this->text;
    }
}

?>