<?php

declare(strict_types=1);

namespace SimPod\GraphQLClient;

use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function Safe\json_encode;

class Client
{
    /** @var string */
    private $endpoint;

    /** @var int */
    private $concurrency;

    /** @var mixed[] */
    private $guzzleConfig;

    /**
     * @param mixed[] $guzzleConfig
     */
    public function __construct(string $endpoint, array $guzzleConfig = [], int $concurrency = 5)
    {
        $this->endpoint     = $endpoint;
        $this->concurrency  = $concurrency;
        $this->guzzleConfig = $guzzleConfig;
    }

    /**
     * @param mixed[]|null $variables
     */
    public function execute(string $query, ?array $variables = null) : ResponseInterface
    {
        $request = $this->createQuery($query, $variables);
        $client  = $this->createClient();

        return $client->send($request);
    }

    /**
     * @param mixed[]|null $variables
     */
    public function createQuery(string $query, ?array $variables = null) : RequestInterface
    {
        $payload = ['query' => $query];
        if ($variables !== null) {
            $payload['variables'] = $variables;
        }

        return new Request('POST', $this->endpoint, [], json_encode($payload));
    }

    public function executeAsync(callable $requests, callable $onFullfilled, callable $onRejected) : void
    {
        $client = $this->createClient();
        $pool   = new Pool($client, $requests(), [
            'concurrency' => $this->concurrency,
            'fulfilled'   => $onFullfilled,
            'rejected'    => $onRejected,
        ]);

        $promise = $pool->promise();
        $promise->wait();
    }

    private function createClient() : \GuzzleHttp\Client
    {
        return new \GuzzleHttp\Client($this->guzzleConfig);
    }
}
