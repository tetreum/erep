<?php

use \App\Controllers\User;
use \App\Controllers\Home;
use \App\Controllers\Company;
use \App\Controllers\Congress;
use \App\Controllers\Market;
use \App\Controllers\PoliticalParty;
use \App\Controllers\WorkOffers;
use \App\Controllers\PrivateMessage;
use \App\Controllers\Newspaper;
use \App\Controllers\Chat;
use \App\Controllers\Militia;
use \App\System\App as AppController;

$ensureLogged = function($request, $response, $next) use ($app)
{
    $app->getContainer()->get("session")->ensureLogged();

    return $next($request, $response);
};

$congressistsOnly = function($request, $response, $next) use ($app)
{
    if (!AppController::user()->isCongressist()) {
        return AppController::container()->get("view")->render($response, "congress/access_restricted.html.twig", []);
    }

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


$app->group('', function () use ($app, $congressistsOnly)
{
    $app->get('/work-offers', function($request, $response, $args) use ($app) {
        $ct = new WorkOffers($app, $response);
        $ct->exec('showList');
    })->setName('workOffers');

    $app->get('/wars', function($request, $response, $args) use ($app) {
        die("ToDo");
        $ct = new Home($app, $response);
        $ct->exec('showWarList');
    })->setName('wars');

    $app->get('/mycompanies', function($request, $response, $args) use ($app) {
        $ct = new Company($app, $response);
        $ct->exec('showMyCompanies');
    })->setName('myCompanies');

    $app->get('/create-company', function($request, $response, $args) use ($app) {
        $ct = new Company($app, $response);
        $ct->exec('showCreate');
    })->setName('createCompany');

    $app->get('/storage', function($request, $response, $args) use ($app) {
        $ct = new User($app, $response);
        $ct->exec('showStorage');
    })->setName('storage');

    $app->get('/gyms', function($request, $response, $args) use ($app) {
        $ct = new User($app, $response);
        $ct->exec('showGyms');
    })->setName('gyms');

    $app->group('/marketplace', function () use ($app)
    {
        $app->get('', function($request, $response, $args) use ($app) {
            $ct = new Market($app, $response);
            $ct->exec('showMarketplaceHome');
        })->setName('marketplace');

        $app->get('/{item}/{quality}', function($request, $response, $args) use ($app) {
            $ct = new Market($app, $response);
            $ct->exec('showItemOffers', $args["item"], $args["quality"]);
        });
    });

    $app->group('news', function () use ($app, $congressistsOnly)
    {
        $app->get('', function($request, $response, $args) use ($app) {
            $ct = new Newspaper($app, $response);
            $ct->exec('showHome');
        })->setName('news');

        $app->get('/create-newspaper', function($request, $response, $args) use ($app) {
            $ct = new Newspaper($app, $response);
            $ct->exec('showCreateForm');
        })->setName('createNewspaper');

        $app->get('/create', function($request, $response, $args) use ($app) {
            $ct = new Newspaper($app, $response);
            $ct->exec('showCreateArticle');
        })->setName('createArticle');
    });

    $app->group('inbox', function () use ($app, $congressistsOnly)
    {
        $app->get('', function($request, $response, $args) use ($app) {
            $ct = new PrivateMessage($app, $response);
            $ct->exec('showInbox');
        })->setName('inbox');

        $app->get('/conversation/:uid', function($request, $response, $args) use ($app) {
            $ct = new PrivateMessage($app, $response);
            $ct->exec('showConversation', $args["uid"]);
        })->setName('privateConversation');
    });

    $app->group('/party', function () use ($app)
    {
        $app->get('', function($request, $response, $args) use ($app) {
            $ct = new PoliticalParty($app, $response);
            $ct->exec('showList');
        })->setName('partyList');

        $app->get('/create', function($request, $response, $args) use ($app) {
            $ct = new PoliticalParty($app, $response);
            $ct->exec('showCreationForm');
        })->setName('partyCreationForm');

        $app->get('/{id}/{slug}', function($request, $response, $args) use ($app) {
            $ct = new PoliticalParty($app, $response);
            $ct->exec('showParty', (int)$args["id"]);
        })->setName('party');
    });

    $app->group('/congress', function () use ($app)
    {
        $app->get('', function($request, $response, $args) use ($app) {
            $ct = new Congress($app, $response);
            $ct->exec('showHome');
        })->setName('congressHome');

        $app->get('/law-proposal/{id}', function($request, $response, $args) use ($app) {
            $ct = new Congress($app, $response);
            $ct->exec('showLawProposal', (int)$args["id"]);
        })->setName('congressLaw');
    })->add($congressistsOnly);

})->add($ensureLogged);


/**
 * API calls, all outputs are expected to be JSON & formatted like:
 * {error: integer, result: mixed}
 * Controller.php#98 will take care of that automatically
 */
$app->group('/api', function () use ($app, $congressistsOnly)
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

    $app->group('/marketplace', function () use ($app)
    {
        $app->get('/list', function($request, $response, $args) use ($app) {
            $ct = new Market($app, $response);
            $ct->json('getList');
        });
        $app->post('/sell', function($request, $response, $args) use ($app) {
            $ct = new Market($app, $response);
            $ct->json('sell');
        });
        $app->post('/buy', function($request, $response, $args) use ($app) {
            $ct = new Market($app, $response);
            $ct->json('buy');
        });
    });

    $app->group('/party', function () use ($app)
    {
        $app->post('/create', function($request, $response, $args) use ($app) {
            $ct = new PoliticalParty($app, $response);
            $ct->json('create');
        });

        $app->post('/join', function($request, $response, $args) use ($app) {
            $ct = new PoliticalParty($app, $response);
            $ct->json('join');
        });

        $app->post('/leave', function($request, $response, $args) use ($app) {
            $ct = new PoliticalParty($app, $response);
            $ct->json('leave');
        });
    });

    $app->group('/congress', function () use ($app)
    {
        $app->post('/apply', function($request, $response, $args) use ($app) {
            $ct = new Congress($app, $response);
            $ct->json('submitApplication');
        });

        $app->post('/law/propose', function($request, $response, $args) use ($app) {
            $ct = new Congress($app, $response);
            $ct->json('proposeLaw');
        });

        $app->post('/law/vote', function($request, $response, $args) use ($app) {
            $ct = new Congress($app, $response);
            $ct->json('voteLaw');
        });
    })->add($congressistsOnly);

    $app->group('/pm', function () use ($app)
    {
        $app->post('/send', function($request, $response, $args) use ($app) {
            $ct = new PrivateMessage($app, $response);
            $ct->json('sendMessage');
        });
    });

    $app->group('/militia', function () use ($app)
    {
        $app->post('/create', function($request, $response, $args) use ($app) {
            $ct = new Militia($app, $response);
            $ct->json('create');
        });

        $app->post('/join', function($request, $response, $args) use ($app) {
            $ct = new Militia($app, $response);
            $ct->json('join');
        });

        $app->post('/leave', function($request, $response, $args) use ($app) {
            $ct = new Militia($app, $response);
            $ct->json('leave');
        });
    });

    $app->group('/newspaper', function () use ($app)
    {
        $app->post('/create', function($request, $response, $args) use ($app) {
            $ct = new Newspaper($app, $response);
            $ct->json('create');
        });

        $app->post('/delete', function($request, $response, $args) use ($app) {
            $ct = new Newspaper($app, $response);
            $ct->json('delete');
        });

        $app->post('/edit', function($request, $response, $args) use ($app) {
            $ct = new Newspaper($app, $response);
            $ct->json('edit');
        });
    });

    $app->group('/chat', function () use ($app)
    {
        $app->get('/get', function($request, $response, $args) use ($app) {
            $ct = new Chat($app, $response);
            $ct->json('showMessages');
        });

        $app->post('/post', function($request, $response, $args) use ($app) {
            $ct = new Chat($app, $response);
            $ct->json('postMessage');
        });

        $app->post('/vote', function($request, $response, $args) use ($app) {
            $ct = new Chat($app, $response);
            $ct->json('vote');
        });

        $app->post('/delete', function($request, $response, $args) use ($app) {
            $ct = new Chat($app, $response);
            $ct->json('delete');
        });
    });

    $app->group('/article', function () use ($app)
    {
        $app->post('/create', function($request, $response, $args) use ($app) {
            $ct = new Newspaper($app, $response);
            $ct->json('createArticle');
        });

        $app->post('/update', function($request, $response, $args) use ($app) {
            $ct = new Newspaper($app, $response);
            $ct->json('updateArticle');
        });

        $app->post('/delete', function($request, $response, $args) use ($app) {
            $ct = new Newspaper($app, $response);
            $ct->json('deleteArticle');
        });

        $app->post('/vote', function($request, $response, $args) use ($app) {
            $ct = new Newspaper($app, $response);
            $ct->json('voteArticle');
        });

        $app->post('/comment', function($request, $response, $args) use ($app) {
            $ct = new Newspaper($app, $response);
            $ct->json('comment');
        });
    });

})->add($ensureLogged);

