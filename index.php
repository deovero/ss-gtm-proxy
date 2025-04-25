<?php
/*
 * Server Side Google Tag Manager Proxy
 * https://github.com/deovero/ss-gtm-proxy
 * Created by DeoVero BV / Jeroen Vermeulen - https://deovero.com
 * Thanks to @jenssegers for his excellent PHP Proxy script which does the heavy lifting.
 */

require_once(__DIR__."/vendor/autoload.php");

$config = require(__DIR__."/config.php");

use Proxy\Proxy;
use Proxy\Adapter\Guzzle\GuzzleAdapter;
use Laminas\Diactoros\ServerRequestFactory;

$request = ServerRequestFactory::fromGlobals()
    ->withHeader('accept-encoding', 'deflate, gzip'); // Prevent zstd, older libcurl can't handle this.

$guzzle = new GuzzleHttp\Client();
$proxy = new Proxy(new GuzzleAdapter($guzzle));

try {
    $response = $proxy->forward($request)->to($config['gtm_url']);
    (new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);
} catch(\GuzzleHttp\Exception\BadResponseException $e) {
    (new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($e->getResponse());
}
