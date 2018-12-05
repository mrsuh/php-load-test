#!/usr/bin/env php

<?php
ini_set('display_errors', 'stderr');

use App\Kernel;
use Spiral\Goridge\SocketRelay;
use Spiral\RoadRunner\PSR7Client;
use Spiral\RoadRunner\Worker;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
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

$kernel                = new Kernel($env, $debug);
$kernel->boot();
$relay                 = new SocketRelay('/tmp/road-runner.sock', null, SocketRelay::SOCK_UNIX);
$psr7                  = new PSR7Client(new Worker($relay));
$httpFoundationFactory = new HttpFoundationFactory();
$diactorosFactory      = new DiactorosFactory();

while ($req = $psr7->acceptRequest()) {
    try {
        $request  = $httpFoundationFactory->createRequest($req);
        $response = $kernel->handle($request);
        $psr7->respond($diactorosFactory->createResponse($response));
        $kernel->terminate($request, $response);
    } catch (\Throwable $e) {
        $psr7->getWorker()->error((string)$e);
    }
}