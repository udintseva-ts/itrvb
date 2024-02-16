<?php
namespace http;

abstract class Response
{
    protected const SUCCESS = true;

    public function send(): void
    {
        $data = ['succuess' => static::SUCCESS] + $this->payload();

        header('Content-Type: application/json');

        echo json_encode($data, JSON_THROW_ON_ERROR);
    }

    abstract protected function payload(): array;
}