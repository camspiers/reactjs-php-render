<?php

$vendorDir = __DIR__.'/vendor';

require $vendorDir . '/autoload.php';
require $vendorDir . '/docopt/docopt/src/docopt.php';

$doc = <<<DOC
ReactJS HTTP Server

Usage:
  server.php [INPUT ...] [-p=]

Options:
  -h --help         Show this screen.
  -p=               Port
DOC;

// Parse the docopt doc block
$args = Docopt\docopt($doc);
$loop = React\EventLoop\Factory::create();

$socket = new React\Socket\Server($loop);
$http = new React\Http\Server($socket);

// Set up react using v8
$react = ReactJS\ReactFactory::createUsingV8(
    $args['INPUT']
);

function validateRequest($data) {
    $errors = [];

    if (empty($data['renderType'])) {
        $errors[] = 'renderType empty';
    } elseif (!in_array($data['renderType'], ['mountable', 'static'])) {
        $errors[] = 'Invalid renderType';
    }

    if (empty($data['componentPath'])) {
        $errors[] = 'componentPath empty';
    }

    if (empty($data['props'])) {
        $errors[] = 'props empty';
    }
    
    return $errors;
}

$http->on('request', function ($request, $response) use ($react) {
    $requestBody = '';
    $headers = $request->getHeaders();
    $contentLength = (int) $headers['Content-Length'];
    $receivedData = 0;

    $request->on('data', function ($data) use (
        $react,
        $request,
        $response,
        &$requestBody,
        &$receivedData,
        $contentLength
    ) {
        $requestBody .= $data;
        $receivedData += strlen($data);
        if ($receivedData >= $contentLength) {
            parse_str($requestBody, $requestData);
            
            $errors = validateRequest($requestData);
            
            if (count($errors)) {
                $response->writeHead(
                    400,
                    ['Content-Type' => 'application/json']
                );
                $response->end(json_encode($errors));
                return;
            }

            $props = json_decode($requestData['props']);
            
            if ($requestData['renderType'] === 'static') {
                $reactFunction = 'renderStaticComponent';
            } else {
                $reactFunction = 'renderMountableComponent';
            }
            
            $markup = $react->$reactFunction(
                $requestData['componentPath'],
                $props
            );

            $response->writeHead(200, array('Content-Type' => 'text/html'));
            $response->end($markup);
        }
    });
});

$socket->listen($args['-p'] ?: 3000);
$loop->run();