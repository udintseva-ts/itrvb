<?php
namespace http\Actions;

use http\Request;
use http\Response;

interface ActionInterface
{
    public function handle(Request $request): Response;
}