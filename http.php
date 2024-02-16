<?php

require 'vendor/autoload.php';

use http\Actions\Comments\CreateComment;
use http\Actions\Likes\CreateCommentLike;
use http\Actions\Likes\CreatePostLike;
use http\Actions\Likes\GetByUuidCommentLikes;
use http\Actions\Likes\GetByUuidPostLikes;
use http\Actions\Posts\CreatePost;
use http\Actions\Posts\DeletePost;
use http\Actions\Users\FindByUsername;
use http\ErrorResponse;
use http\Request;


ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $request = new Request($_GET, $_POST, $_SERVER);
} catch (Exception $ex) {
    (new ErrorResponse($ex->getMessage()))->send();
    return;
}

try {
    $path = $request->path();
} catch (Exception $ex) {
    (new ErrorResponse($ex->getMessage()))->send();
    return;
}

try {
    $method = $request->method();
} catch (Exception $ex) {
    (new ErrorResponse($ex->getMessage()))->send();
    return;
}

$routs = [
    'GET' => [
        '/users/show' => FindByUsername::class,
        '/likes/comment' => GetByUuidCommentLikes::class,
        '/likes/post' => GetByUuidPostLikes::class,
    ],
    'POST' => [
        '/posts/comment' => CreateComment::class,
        '/posts/' => CreatePost::class,
        '/likes/post/' => CreatePostLike::class,
        '/likes/comment/' => CreateCommentLike::class
    ],
    'DELETE' => [
        '/posts' => DeletePost::class
    ]
];

if (!array_key_exists($method, $routs) || !array_key_exists($path, $routs[$method])) {
    (new ErrorResponse('Not found path'))->send();
    return;
}

$actionClassName = $routs[$method][$path];

$action = $container->get($actionClassName);

try {
    $response = $action->handle($request);
} catch (Exception $ex) {
    (new ErrorResponse($ex->getMessage()))->send();
    return;
}

$response->send();