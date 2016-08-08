<?php

/**
 * Abstract controller associated a user request
 * @package App\System
 */
namespace App\System;

use Slim\Http\Request;

abstract class Controller
{
    /**
     * @var \Slim\App
     */
    public $app = null;

    /**
     * @var \Slim\Container
     */
    public $container = null;

    /**
     * Shortcut to the request functionalities
     *
     * @var Request
     */
    public $req = null;

    // Shortcut to know whether I'm logged or not.
    public $isLogged = null;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    public $response = null;

    public function __construct($app = null, $response)
    {

        if (empty($app)) {
            $app = App::getInstance();
        }

        $this->app = $app;
        $this->container = $app->getContainer();
        $this->response = $response;

        $this->req = $this->container->get("request");

        $this->isLogged = $this->container->get('session')->isLogged();
    }

    /**
     * Renders a template
     * @param string $file
     * @param array $vars
     * @return mixed
     */
    public function render ($file, $vars = [])
    {
        $vars["controller"] = str_replace('App\\Controllers\\', "", get_class($this));

        if (App::session()->isLogged()) {
            $user = App::session()->getUser();
            $user["money"] = App::session()->getMoney();
            $user["location"] = App::session()->getLocation();
            $vars["my"] = $user;
        }

        return $this->container->get("view")->render($this->response, $file, $vars);
    }

    /**
     * Executes a controller method (check routes.php)
     * @return mixed
     * @throws \Exception
     */
    private function run()
    {
        $numargs = func_num_args();
        $args = func_get_args();

        if ($numargs == 0) {
            throw new \Exception("The run() method requires at least one parameter, which is the method name to execute.");
        }

        $method = array_shift($args);
        return call_user_func_array(array($this, $method), $args);
    }

    /**
     * Execute a method from a controller, and display its output
     *
     * NOTE: A possible use of this wrapper function could be to determine if the request is a regular HTTP one or an AJAX one, and pass this variable to the view.
     *
     */
    public function exec()
    {
        call_user_func_array(array($this, "run"), func_get_args());
    }

    /**
     * Print the return value of the controller function as JSON (and previously set the content-type header)
     */
    public function json()
    {
        $result = call_user_func_array(array($this, "run"), func_get_args());

        if (Utils::isAPIcall() && gettype($result) != "object")
        {
            $response = new \stdClass();
            $response->error = 0;
            $response->result = $result;
            $result = $response;
        }

        Utils::jsonResponse($result);
        /*
         * Right response should be like this, but doesnt work, so fuck phpSlim wrappers.
         * http://www.slimframework.com/docs/objects/router.html
         */
        /*
        return $this->response->withHeader('Content-Type', 'application/json')
                                ->write(json_encode($result));
        */
    }
}
