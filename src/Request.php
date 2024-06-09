<?php

namespace Ghaith8bit\WebServer;

class Request
{
    protected $method = null;
    protected $uri = null;
    protected $parameters = [];
    protected $headers = [];

    private function __construct($method, $uri, $headers = [])
    {
        $this->method = strtoupper($method);
        $this->headers = $headers;

        list($this->uri, $params) = explode('?', $uri, 2);
        parse_str($params ?? '', $this->parameters);
    }

    public static function withHeaderString($headerString)
    {
        $lines = explode("\n", $headerString);

        if (empty($lines)) {
            throw new \InvalidArgumentException("Invalid header string.");
        }

        $requestLine = array_shift($lines);
        list($method, $uri) = explode(' ', $requestLine, 2);


        if (empty($method) || empty($uri)) {
            throw new \InvalidArgumentException("Invalid request line in header string.");
        }


        $headers = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (str_contains($line, ': ')) {
                list($key, $value) = explode(': ', $line, 2);
                $headers[$key] = $value;
            }
        }

        return new self($method, $uri, $headers);
    }

    public function method()
    {
        return $this->method;
    }

    public function uri()
    {
        return $this->uri;
    }

    public function param($key, $default = null)
    {
        return $this->parameters[$key] ?? $default;
    }

    public function header($key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }
}
