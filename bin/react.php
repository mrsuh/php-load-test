#!/usr/bin/env php

<?php

use App\Kernel;

require __DIR__.'/../vendor/autoload.php';

$loop = React\EventLoop\Factory::create();
$kernel = new Kernel('prod', false);
$server = new React\Http\Server(function (Psr\Http\Message\ServerRequestInterface $request) use ($kernel) {

    $method = $request->getMethod();
    $headers = $request->getHeaders();
    $content = $request->getBody();
    $post = [];
    if (in_array(strtoupper($method), array('POST', 'PUT', 'DELETE', 'PATCH')) &&
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