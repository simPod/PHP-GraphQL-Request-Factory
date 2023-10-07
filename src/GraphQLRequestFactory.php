<?php

declare(strict_types=1);

namespace SimPod\GraphQLRequestFactory;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

use function is_string;
use function Safe\json_encode;

final class GraphQLRequestFactory
{
    public function __construct(
        private RequestFactoryInterface $requestFactory,
        private StreamFactoryInterface $streamFactory,
    ) {
    }

    /**
     * Creates POST Request with application/json Content-Type header
     *
     * @param array<string, mixed> $variables
     */
    public function createRequest(
        string $uri,
        StreamInterface|string $query,
        array|null $variables = null,
        string|null $operationName = null,
    ): RequestInterface {
        $request = $this->requestFactory->createRequest('POST', $uri);

        $body = ['query' => is_string($query) ? $query : (string) $query];
        if ($variables !== null) {
            $body['variables'] = $variables;
        }

        if ($operationName !== null) {
            $body['operationName'] = $operationName;
        }

        return $request
            ->withHeader('Content-Type', 'application/json')
            ->withBody(
                $this->streamFactory->createStream(json_encode($body)),
            );
    }
}
