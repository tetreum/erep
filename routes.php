<?php

use \App\Controllers\User;
use \App\Controllers\Home;
use \App\Controllers\Company;
use \App\Controllers\WorkOffers;

$ensureLogged = function($request, $response, $next) use ($app)
{
    $app->getContainer()->get("session")->ensureLogged();

    return $next($request, $response);
};

$app->get('/', function($request, $response, $args) use ($app) {
    $ct = new Home($app, $response);
    $ct->exec('showHomepage');
})->setName('home')->add($ensureLogged);

$app->get('/login', function($request, $response, $args) use ($app) {
    $ct = new User($app, $response);
    $ct->exec('showLogin');
})->setName('login');

$app->post('/login', function($request, $response, $args) use ($app) {
    $ct = new User($app, $response);
    $ct->json('doLogin');
});

$app->get('/logout', function($request, $response, $args) use ($app) {
    $ct = new User($app, $response);
    $ct->exec('logout');
})->setName('logout');

$app->get('/signup', function($request, $response, $args) use ($app) {
    $ct = new User($app, $response);
    $ct->exec('showSignup');
})->setName('signup');

$app->post('/signup', function($request, $response, $args) use ($app) {
    $ct = new User($app, $response);
    $ct->json('signup');
});


$app->group('', function () use ($app)
{
    $app->get('/work-offers', function($request, $response, $args) use ($app) {
        $ct = new WorkOffers($app, $response);
        $ct->exec('showList');
    })->setName('workOffers');

    $app->get('/wars', function($request, $response, $args) use ($app) {
        $ct = new Home($app, $response);
        $ct->exec('showWarList');
    })->setName('wars');

    $app->get('/mycompanies', function($request, $response, $args) use ($app) {
        $ct = new Home($app, $response);
        $ct->exec('showMyCompanies');
    })->setName('mycompanies');

})->add($ensureLogged);


/**
 * API calls, all outputs are expected to be JSON & formatted like:
 * {error: integer, result: mixed}
 * Controller.php#98 will take care of that automatically
 */
$app->group('/api', function () use ($app)
{
    $app->group('/user', function () use ($app)
    {
        $app->post('/work', function($request, $response, $args) use ($app) {
            $ct = new User($app, $response);
            $ct->json('work');
        });

        $app->post('/train', function($request, $response, $args) use ($app) {
            $ct = new User($app, $response);
            $ct->json('train');
        });
    });

    $app->group('/work_offers', function () use ($app)
    {
        $app->get('/list', function($request, $response, $args) use ($app) {
            $ct = new WorkOffers($app, $response);
            $ct->json('getList');
        });
        $app->post('/create', function($request, $response, $args) use ($app) {
            $ct = new WorkOffers($app, $response);
            $ct->json('create');
        });
        $app->post('/accept', function($request, $response, $args) use ($app) {
            $ct = new WorkOffers($app, $response);
            $ct->json('accept');
        });
        $app->post('/delete', function($request, $response, $args) use ($app) {
            $ct = new WorkOffers($app, $response);
            $ct->json('delete');
        });
    });

    $app->group('/company', function () use ($app)
    {
        $app->get('/list', function($request, $response, $args) use ($app) {
            $ct = new Company($app, $response);
            $ct->json('getList');
        });
        $app->post('/create', function($request, $response, $args) use ($app) {
            $ct = new Company($app, $response);
            $ct->json('create');
        });
        $app->post('/sell', function($request, $response, $args) use ($app) {
            $ct = new Company($app, $response);
            $ct->json('accept');
        });
        $app->post('/delete', function($request, $response, $args) use ($app) {
            $ct = new Company($app, $response);
            $ct->json('delete');
        });
    });
})->add($ensureLogged);

