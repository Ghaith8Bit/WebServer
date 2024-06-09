<?php

use Ghaith8bit\WebServer\Request;
use Ghaith8bit\WebServer\Response;
use Ghaith8bit\WebServer\Server;

require_once 'bootstrap.php';

$port = $argv[1] ?? $_ENV['PORT'] ?? 8000;

try {
    $server = new Server('127.0.0.1', $port);

    echo "Server started on http://127.0.0.1:$port\n";

    $server->listen(function (Request $request) {
        return new Response('Response Message');
    });
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
