<?php

require_once 'bootstrap.php';

$port = $argv[1] ?? $_ENV['PORT'] ?? 8000;

try {
    $server = new Server('127.0.0.1', $port);

    $server->listen(function (Request $request) {
        return new Response('Response Message');
    });

    echo "Server started on http://127.0.0.1:$port\n";
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
