<?php

ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

define('APP_ROOT', __DIR__ . DIRECTORY_SEPARATOR);

return [
    "mysql" => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'erepublik',
        'username' => 'root',
        'password' => 'PASSWORD',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ],
    "password_hash" => "!·$%&3456jsdehg..",
    'mode' => 'development',
    'displayErrorDetails' => true,
    'debug' => true,
    'cookies.encrypt' => false,
    'cookies.secret_key' => '45r67t4e56uyhtagdfhg-.khj',
    'cookies.cipher' => MCRYPT_RIJNDAEL_256,
    'cookies.cipher_mode' => MCRYPT_MODE_CBC,
    'cookies.path' => '/',
    'cookies.domain' => "erepublik.dev",
];