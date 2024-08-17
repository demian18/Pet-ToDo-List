<?php

namespace Core;

class Request
{
    public function all(): array
    {
        return array_merge($_GET, $_POST);
    }
    public function input(string $key, $default = null)
    {
        return $this->all()[$key] ?? $default;
    }
    public function has(string $key): bool
    {
        return isset($this->all()[$key]);
    }
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    public function fullUrl(): string
    {
        return $_SERVER['REQUEST_URI'];
    }
    public function header(string $header, $default = null)
    {
        $headerKey = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
        return $_SERVER[$headerKey] ?? $default;
    }
    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }
    public function query(): array
    {
        return $_GET;
    }
    public function post(): array
    {
        return $_POST;
    }
}