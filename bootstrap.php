<?php

use \Slim\App as Slim;
use \App\System\Session;
use \App\System\App;

// Set internal encoding to what should be the default
mb_internal_encoding("UTF-8");

/**
 * Config array for Slim framework
 * Define the basic constants that the application needs
 */
$config = require dirname(__FILE__) . '/conf.php';

define('APP_HTDOCS_PATH', APP_ROOT . 'htdocs/');
define('APP_TEMPLATES_PATH', APP_ROOT . 'templates/');

require APP_ROOT . 'vendor/autoload.php';

$app = new Slim(['settings' => $config]);
App::$slim = $app;

$container = $app->getContainer();

/**
 * Add resources to the app. These resources will be needed at any point throughout the execution.
 */
$container['session'] = function () use ($app) {
    return new Session($app);
};
$container['view'] = function ($container) {
    $cache = '../tmp/cache';

    if ($container->get("settings")["mode"] == "development") {
        $cache = false;
    }

    $view = new \Slim\Views\Twig(APP_TEMPLATES_PATH, [
        'debug' => true,
        'cache' => $cache
    ]);

    $view->addExtension(new Twig_Extensions_Extension_I18n());
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));
    if ($container->get("settings")["mode"] == "development") {
        $view->addExtension(new Twig_Extension_Debug());
    }

    return $view;
};

$container['db'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['mysql']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

/*
 * Add some global vars before rendering each template
 */
$app->add(function ($request, $response, $next) use ($app) {

    $view = App::container()->get("view");
    $view->getEnvironment()->addGlobal('isAjax', $app->isAjax);

    //$response = $next($request, $response);
    try {
        $response = $next($request, $response);
    } catch (\Exception $e)
    {
        if ($e->getCode() == 11 && !$app->isAjax) { // Authentication failed
            App::redirect("/login");
        }
        if ($app->isAjax) {
            $response = new \stdClass();
            $response->error = $e->getCode();
            $response->message = $e->getMessage();

            \App\System\Utils::jsonResponse($response);
        }
    }

    return $response;
});
App::container()->get("db")->getConnection();