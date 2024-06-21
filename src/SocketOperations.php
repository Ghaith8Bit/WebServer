<?php

namespace Ghaith8bit\WebServer;

use Socket;

trait SocketOperations
{

    public function createSocket(): Socket
    {
        $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if ($socket === false) {
            $this->handleSocketError("Failed to create socket");
        }

        return $socket;
    }

    public function bindSocket($socket, $host, $port)
    {
        if (!socket_bind($socket, $host, $port)) {
            $this->handleSocketError("Failed to bind socket to $host:$port ", $socket);
        }
    }

    public function listenSocket($socket)
    {
        if (!socket_listen($socket)) {
            $this->handleSocketError("Failed to listen on socket ", $socket);
        }
    }

    public function acceptSocket($socket)
    {
        $client = @socket_accept($socket);

        if ($client === false) {
            $this->handleSocketWarning("Failed to accept connection", $socket);
            return false;
        }

        echo "Connection accepted\n";
        return $client;
    }

    public function readSocket($clientSocket)
    {
        $requestString = '';
        while (($chunk = socket_read($clientSocket, 2048)) !== false) {
            $requestString .= $chunk;
            if (strpos($requestString, "\r\n\r\n") !== false) {
                break;
            }
        }

        if ($requestString === false) {
            $this->handleSocketWarning("Failed to read request", $clientSocket);
            $this->closeSocket($clientSocket);
            return false;
        }

        echo "Request received: $requestString\n";
        return $requestString;
    }

    public function writeSocket($clientSocket, $response)
    {
        $responseString = (string) $response;
        socket_write($clientSocket, $responseString, strlen($responseString));
        echo "Response sent: $responseString\n";
    }

    public function closeSocket($socket)
    {
        if ($socket !== null) {
            socket_close($socket);
            echo "Connection closed\n";
        }
    }

    protected function handleSocketError($message, $socket = null)
    {
        $errorMessage = socket_strerror(socket_last_error($socket));
        throw new \RuntimeException("$message: $errorMessage");
    }

    protected function handleSocketWarning($message, $socket = null)
    {
        $errorMessage = socket_strerror(socket_last_error($socket));
        echo "$message: $errorMessage\n";
    }
}
