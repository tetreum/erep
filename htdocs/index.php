<?php

use \App\System\App;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


// Redirect visits containing "index.php/path/to/controller" to "/path/to/controller"
// TODO can it be done at server config level?
if (preg_match('/^\/index\.php/', $_SERVER['REQUEST_URI'])) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . preg_replace('/^\/index\.php/', '', $_SERVER['REQUEST_URI']));
    exit;
}

require "../bootstrap.php";

$app->add(function (ServerRequestInterface $request, ResponseInterface $response, callable $next) use ($app) {

    if ($request->isXhr()) {
        $app->isAjax = true;
    } else {
        $app->isAjax = false;
    }

    return $next($request, $response);
});

require '../routes.php';

// Init the application
$app->run();
