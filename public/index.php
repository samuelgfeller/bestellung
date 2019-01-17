<?php


function baseurl($path = '', $full = false) {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $baseUri = dirname(dirname($scriptName));
    $result = str_replace('\\', '/', $baseUri) . $path;
    $result = str_replace('//', '/', $result);
    if ($full === true) {
        $result = hosturl() . $result;
    }

    return $result;
}

function hosturl(){
    $server = $_SERVER;
    $host = $server['SERVER_NAME'];
    $port = $server['SERVER_PORT'];
    $result = (isset($server['HTTPS']) && $server['HTTPS'] != "off") ? "https://" : "http://";
    $result .= ($port == '80' || $port == '443') ? $host : $host . ":" . $port;
    return $result;
}
//
// Get the URI path
//
$path = parse_url($_SERVER['REQUEST_URI'])['path'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$scriptName = dirname($scriptName);
$len = strlen($scriptName);

if (($len > 0 && $scriptName !== '/') || $scriptName !== "\\") {
    $path = substr($path, $len);
}
session_start();

require_once __DIR__."/../ajax_controller.php";
require_once __DIR__."/../controller.php";
//require_once __DIR__."/../templates/base.html.php";

header("HTTP/1.0 404 Not Found");
require_once __DIR__.'/../templates/pages/error.html.php';
//echo '404 Not Found';