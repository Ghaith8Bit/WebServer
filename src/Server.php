<?php

namespace Ghaith8bit\WebServer;

class Server
{
    protected $socket;
    protected $host;
    protected $port;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->socket = $this->createSocket();
        $this->bindSocket();
    }

    public function listen($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('The given argument should be callable.');
        }

        if (!socket_listen($this->socket)) {
            $errorMessage = socket_strerror(socket_last_error($this->socket));
            throw new \RuntimeException("Failed to listen on socket: $errorMessage");
        }

        while (1) {

            $client = @socket_accept($this->socket);
            if ($client === false) {
                $errorMessage = socket_strerror(socket_last_error($this->socket));
                echo "Failed to accept connection: $errorMessage\n";
                continue;
            }

            $requestString = socket_read($client, 1024, PHP_NORMAL_READ);
            if ($requestString === false) {
                $errorMessage = socket_strerror(socket_last_error($client));
                echo "Failed to read request: $errorMessage\n";
                socket_close($client);
                continue;
            }

            $request = Request::withHeaderString($requestString);
            $response = call_user_func($callback, $request);

            if (!$response instanceof Response) {
                $response = Response::error(404);
            }

            $responseString = (string) $response;
            socket_write($client, $responseString, strlen($responseString));
            socket_close($client);
        }
    }

    protected function createSocket()
    {
        $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if ($socket === false) {
            $errorMessage = socket_strerror(socket_last_error());
            throw new \RuntimeException("Failed to create socket: $errorMessage");
        }

        return $socket;
    }

    protected function bindSocket()
    {
        if (!socket_bind($this->socket, $this->host, $this->port)) {
            $errorMessage = socket_strerror(socket_last_error($this->socket));
            throw new \RuntimeException("Failed to bind socket to $this->host:$this->port: $errorMessage");
        }
    }

    public function __destruct()
    {
        if ($this->socket !== null) {
            socket_close($this->socket);
        }
    }
}
