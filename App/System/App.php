<?php

namespace App\System;

use Slim\Slim;

/**
 * Helper to access Slim container easily with some helpful methods
 *
 * Class App
 */
class App
{
    const CURRENT_VERSION = "0.3";

    /**
     * @var \Slim\App
     */
    public static $slim;

    /**
     * Resolves an instance of a slim object
     *
     * @param $key
     * @return mixed
     */
    public static function make($key)
    {
        return self::getInstance()->$key;
    }

    /**
     * @return null|Slim
     */
    public static function getInstance()
    {
        return self::$slim;
    }

    /**
     * Gets a config key
     *
     * @param $key
     * @return mixed
     */
    public static function config($key)
    {
        return self::getInstance()->config($key);
    }

    /**
     * @param $name
     * @param $value
     * @param null $time
     * @param null $path
     * @param null $domain
     * @param null $secure
     * @param null $httponly
     */
    public static function setCookie(
        $name,
        $value,
        $time = null,
        $path = null,
        $domain = null,
        $secure = null,
        $httponly = null
    ) {
        self::getInstance()->setCookie($name, $value, $time, $path, $domain, $secure, $httponly);
    }

    /**
     * Get route for an specified route name.
     * If you explicitly set lang it will be overided.
     *
     * @param $route
     * @param array $params
     * @return string
     */
    public static function route($route, $params = array(), $withBase = false)
    {
        $app = self::getInstance();

        $params = array_merge(array('lang' => self::getLang()), $params);

        $url = $app->urlFor($route, $params);

        if ($withBase) {
            $url = $app->request->getUrl() . $url;
        }

        return $url;
    }

    /**
     * Redirect user to given url
     * @param string $url
     */
    public static function redirect ($url)
    {
        header("Location: $url");
        exit;
    }

    /**
     * @return mixed
     */
    public static function getLang()
    {
        return self::getInstance()->langManager->getLocale();
    }

    public static function container () {
        return App::getInstance()->getContainer();
    }

    /**
     * Get latest Mongo.lo version available
     * @return array
     */
    public static function info ()
    {
        try {
            $latest = json_decode(@file_get_contents("https://cdn.rawgit.com/tetreum/mongolo/master/version.json"));
            if ($latest == null) {
                throw new \Exception("failed to fetch");
            }
        } catch (\Exception $e) {
            $latest = new \stdClass();
            $latest->version = "failed to fetch";
        }

        return [
            "current" => self::CURRENT_VERSION,
            "latest" => $latest
        ];
    }

    /***
     * 1. Download the zipped version
     * 2. Unzip it in /tmp/
     * 3. Check in /migration/ folder if there is a file to execute for this upgrade, ex: 0_1-to-0_2.php
     * 4. Move /tmp/ folder to current path
     */
    public function upgrade () {
    }

}
