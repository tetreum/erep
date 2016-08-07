<?php

namespace App\System;

class Utils
{
    const METHOD_GET = "get";
    const METHOD_POST = "post";
    const METHOD_PUT = "put";

    /**
     * @param \stdClass $response
     */
    public static function jsonResponse ($response)
    {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    /**
     * Checks if requested call is from api routes
     * @return bool
     */
    public static function isAPIcall ()
    {
        if (substr($_SERVER['REQUEST_URI'], 0, 5) == "/api/") {
            return true;
        }
        return false;
    }

    /**
     * Checks if given date is valid
     * @param string $date
     * @return bool
     */
    public static function isValidDate ($date)
    {
        try {
            new \DateTime($date);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    /*
     * Prints any kind of var in any environment
     * **/
    public static function p()
    {
        $consolePrint = false;

        if (!isset($_SERVER['HTTP_HOST']) || $_SERVER['HTTP_HOST'] == null) {
            $consolePrint = true;
        }

        if (!$consolePrint) {
            echo '<pre>';
        }
        $args = func_get_args();

        foreach ($args as $var)
        {
            if ($var == null || $var == '') {
                var_dump($var);
            } elseif (is_array($var) || is_object($var)) {
                print_r($var);
            } else {
                echo $var;
            }
            if (!$consolePrint) {
                echo '<br>';
            } else {
                echo "\n";
            }
        }
        if (!$consolePrint) {
            echo '</pre>';
        }
    }

    /**
     * Sends curls queries
     * @param string $url
     * @param string $method
     * @param array $data
     * @return mixed
     */
    public static function curl($url, $method = "get", $data = [])
    {
        $ch = curl_init($url);
        $header = array();
        $header[0]  = "Accept: text/xml,application/xml,application/xhtml+xml,";
        $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header[]   = "Cache-Control: max-age=0";
        $header[]   = "Connection: keep-alive";
        $header[]   = "Keep-Alive: 300";
        $header[]   = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header[]   = "Accept-Language: en-us,en;q=0.5";
        $header[]   = "Pragma: "; // browsers keep this blank.

        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        switch ($method)
        {
            case self::METHOD_POST:
                curl_setopt($ch, CURLOPT_POST, true);

                if (sizeof($data) > 0) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                }
                break;
            case self::METHOD_PUT:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                if (sizeof($data) > 0) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                }
                break;
            default:
                if (sizeof($data) > 0) {
                    $url = $url . "?" . http_build_query($data);
                }
                break;
        }

        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);

        return curl_exec($ch);
    }
}