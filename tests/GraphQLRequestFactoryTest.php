<?php

declare(strict_types=1);

namespace SimPod\GraphQLRequestFactory\Tests;

use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use SimPod\GraphQLRequestFactory\GraphQLRequestFactory;

final class GraphQLRequestFactoryTest extends TestCase
{
    /**
     * @param array<mixed> $arguments
     *
     * @dataProvider provideCreateRequestIsValid
     */
    public function testCreateRequestIsValid(string $expectedQuery, array $arguments): void
    {
        $psr17Factory          = new Psr17Factory();
        $graphQLRequestFactory = new GraphQLRequestFactory($psr17Factory, $psr17Factory);

        $request = $graphQLRequestFactory->createRequest('', ...$arguments);

        self::assertJsonStringEqualsJsonString(
            $expectedQuery,
            (string) $request->getBody()
        );

        self::assertSame(
            'application/json',
            $request->getHeader('Content-Type')[0]
        );
    }

    /**
     * @return iterable<string, array<mixed>>
     */
    public function provideCreateRequestIsValid(): iterable
    {
        yield 'query' => [
            <<<JSON
{
    "query": "graphql query"
}
JSON
,
            ['graphql query'],
        ];

        yield 'query with variables' => [
            <<<JSON
{
    "query": "graphql query",
    "variables": {"key": "value"}
}
JSON
,
            ['graphql query', ['key' => 'value']],
        ];

        yield 'query with variables and operation name' => [
            <<<JSON
{
    "query": "graphql query",
    "variables": {"key": "value"},
    "operationName": "operation"
}
JSON
,
            ['graphql query', ['key' => 'value'], 'operation'],
        ];

        yield 'query as Stream' => [
            <<<JSON
{
    "query": "graphql query"
}
JSON
,
            [(new Psr17Factory())->createStream('graphql query')],
        ];
    }
}
