<?php

declare(strict_types=1);

namespace SimPod\GraphQLRequestFactory;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use function Safe\json_encode;

final class GraphQLRequestFactory
{
    /** @var RequestFactoryInterface */
    private $requestFactory;

    /** @var StreamFactoryInterface */
    private $streamFactory;

    public function __construct(RequestFactoryInterface $requestFactory, StreamFactoryInterface $streamFactory)
    {
        $this->requestFactory = $requestFactory;
        $this->streamFactory  = $streamFactory;
    }

    /**
     * Creates POST Request with application/json Content-Type header
     *
     * @param array<string, mixed> $variables
     */
    public function createRequest(string $uri, string $query, ?array $variables = null, ?string $operationName = null) : RequestInterface
    {
        $request = $this->requestFactory->createRequest('POST', $uri);

        $body = ['query' => $query];
        if ($variables !== null) {
            $body['variables'] = $variables;
        }

        if ($operationName !== null) {
            $body['operationName'] = $operationName;
        }

        return $request
            ->withHeader('Content-Type', 'application/json')
            ->withBody(
                $this->streamFactory->createStream(json_encode($body))
            );
    }
}
