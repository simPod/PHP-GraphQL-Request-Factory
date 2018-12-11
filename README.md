# PHP GraphQL Client

[![Build Status](https://travis-ci.org/simPod/GraphQL-Client.svg)](https://travis-ci.org/simPod/GraphQL-Client)
[![Downloads](https://poser.pugx.org/simpod/graphql-client/d/total.svg)](https://packagist.org/packages/simpod/graphql-client)
[![Packagist](https://poser.pugx.org/simpod/graphql-client/v/stable.svg)](https://packagist.org/packages/simpod/graphql-client)
[![Licence](https://poser.pugx.org/simpod/graphql-client/license.svg)](https://packagist.org/packages/simpod/graphql-client)
[![Quality Score](https://scrutinizer-ci.com/g/simPod/GraphQL-Client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/simPod/GraphQL-Client)
[![Code Coverage](https://scrutinizer-ci.com/g/simPod/GraphQL-Client/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/simPod/GraphQL-Client)

## Installation

Add as [Composer](https://getcomposer.org/) dependency:

```sh
composer require simpod/graphql-client
```

## Usage

```php
<?php

declare(strict_types=1);

namespace YourNameSpace;

use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use SimPod\GraphQLClient\Client;
use function json_decode;
use const PHP_EOL;

class YourClient
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function runSingleQuery() : array
    {
        $query = <<<'GRAPHQL'
query GetHuman($id: ID!) {
  human(id: $id) {
    name
    appearsIn
    starships {
      name
    }
  }
}
GRAPHQL;

        $psrResponse = $this->client->execute($query, ['id' => 1002]);

        $jsonResponseBody = $psrResponse->getBody()->getContents();

        return json_decode($jsonResponseBody, true);
    }

    public function runMultipleQueries() : void
    {
        $query = <<<'GRAPHQL'
query GetHuman($id: ID!) {
  human(id: $id) {
    name
    appearsIn
    starships {
      name
    }
  }
}
GRAPHQL;

        $requests = function () use ($query) {
            foreach ([1001, 1002, 1003] as $id) {
                yield $this->client->createQuery(
                    $query,
                    ['id' => $id]
                );
            }
        };

        $this->client->executeAsync($requests,
            self::createOnFullfilledCallback(),
            self::createOnRejectedCallback()
        );
    }

    private static function createOnFullfilledCallback() : callable
    {
        return static function (ResponseInterface $response) : void {
            $responseBody = json_decode($response->getBody()->getContents(), true);

            echo $responseBody . PHP_EOL;
        };
    }

    private static function createOnRejectedCallback() : callable
    {
        return static function (RequestException $requestException) : void {
            echo $requestException->getMessage() . PHP_EOL;
        };
    }
}

```

The client class accepts following params:

- endpoint
- concurrency (optional) ([for concurrent requests](http://docs.guzzlephp.org/en/stable/quickstart.html?highlight=pool#concurrent-requests))
- Guzzle client config (optional) ([client config](https://github.com/guzzle/guzzle/blob/master/src/Client.php#L62))

```php

new GraphQLClient('https://example.com/graphql', 10, [ 'timeout' => 30 ]);
```
