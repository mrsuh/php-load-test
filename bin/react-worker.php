#!/usr/bin/env php

<?php

use App\Kernel;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../config/bootstrap.php';

$env   = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'dev';
$debug = (bool)($_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? ('prod' !== $env));

if ($debug) {
    umask(0000);

    Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts(explode(',', $trustedHosts));
}

$loop   = React\EventLoop\Factory::create();
$kernel = new Kernel($env, $debug);
$server = new React\Http\Server(function (Psr\Http\Message\ServerRequestInterface $request) use ($kernel) {

    $method  = $request->getMethod();
    $headers = $request->getHeaders();
    $content = $request->getBody();
    $post    = [];
    if (in_array(strtoupper($method), ['POST', 'PUT', 'DELETE', 'PATCH']) &&
        isset($headers['Content-Type']) && (0 === strpos($headers['Content-Type'], 'application/x-www-form-urlencoded'))
    ) {
        parse_str($content, $post);
    }
    $sfRequest = new Symfony\Component\HttpFoundation\Request(
        $request->getQueryParams(),
        $post,
        [],
        $request->getCookieParams(),
        $request->getUploadedFiles(),
        [],
        $content
    );
    $sfRequest->setMethod($method);
    $sfRequest->headers->replace($headers);
    $sfRequest->server->set('REQUEST_URI', $request->getUri());

    if (isset($headers['Host'])) {
        $sfRequest->server->set('SERVER_NAME', current($headers['Host']));
    }
    $sfResponse = $kernel->handle($sfRequest);

    $kernel->terminate($sfRequest, $sfResponse);

    return new React\Http\Response(
        $sfResponse->getStatusCode(),
        $sfResponse->headers->all(),
        $sfResponse->getContent()
    );
});

$socket = new React\Socket\Server('tcp://0.0.0.0:9000', $loop);
$server->listen($socket);

echo "Server running at tcp://0.0.0.0:9000\n";

$loop->run();