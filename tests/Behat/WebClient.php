<?php

namespace App\Tests\Behat;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class WebClient
{
    private KernelInterface $kernel;
    private ?Response $response;

    public function __construct(KernelInterface $kernel)
    {
        $this->response = null;
        $this->kernel = $kernel;
    }

    public function fetch(string $path, string $method = 'GET')
    {
        $request = Request::create($path, $method);
        $this->response = $this->kernel->handle($request);
    }

    public function getLatestResponseContent(): array
    {
        return json_decode($this->response->getContent(), true);
    }
}
