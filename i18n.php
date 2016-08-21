<?php

/**
 * Variable intertpolator for PHP, meant to be executed after translation function.
 *
 * E.g: message: "Hi %name%, you have %numPending% pending movies."
 *      vars array: "name" => "Pau", "numPending" => 45
 *
 * @param  string $message Trenslated message. It may include placeholders in the form of %name%.
 * @param  array  $vars    Variables to include in the message. Keys are the placeholders, values are the variable values.
 * @return string          Interpolated message
 */
function tprintf($message, $vars = array())
{
    preg_match_all('/%(\w+)%/', $message, $matches, PREG_SET_ORDER);

    $replacements = array();
    foreach ($matches as $match) {
        $replacements[$match[0]] = $vars[$match[1]];
    }

    return strtr($message, $replacements);
}

/**
 * Translate function recognized by the message extractor
 * @param  string $message Message to translate
 * @return string          translated message
 */
function __($message)
{
    return \App\System\App::container()->get('i18n')->getTranslator()->translate($message);
}

/**
 * Translate function with plural forms recognized by the message extractor
 * @param  string $singular Message to translate (singular form)
 * @param  string $plural   Message to translate (plural form)
 * @param  int $number   Number to determine message form
 * @return string           Translated message
 */
function __p($singular, $plural, $number)
{
    return \App\System\App::container()->get('i18n')->getTranslator()->translatePlural($singular, $plural, $number);
}