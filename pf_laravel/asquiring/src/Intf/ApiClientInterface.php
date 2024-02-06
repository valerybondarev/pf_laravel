<?php

namespace App\Intf;

interface ApiClientInterface
{
    public function send(string $method, string $url, array $dataArray = [], array $queryParameters = []): string;

    public function withSystem(string $system): ApiClientInterface;

    public function auth(bool $needAuth): self;
}
