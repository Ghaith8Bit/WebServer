<?php

namespace Ghaith8bit\WebServer;

use Socket;

class Server
{
    use SocketOperations;

    protected Socket $socket;
    protected string $host;
    protected int $port;

    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->socket = $this->createSocket();
        $this->bindSocket($this->socket, $this->host, $this->port);
    }

    public function listen(callable $callback): void
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('The given argument should be callable.');
        }

        $this->listenSocket($this->socket);


        echo "Server is listening on http://{$this->host}:{$this->port}\n";

        while (1) {

            $client = $this->acceptSocket($this->socket);

            if (!$client) {
                continue;
            }


            $requestString = $this->readSocket($client);

            if (!$requestString) {
                continue;
            }

            $request = Request::fromRequestString($requestString);
            $response = call_user_func($callback, $request);

            if (!$response instanceof Response) {
                $response = Response::error(404);
            }

            $this->writeSocket($client, $response);
            $this->closeSocket($client);
        }
    }

    public function __destruct()
    {
        self::closeSocket($this->socket);
    }
}
