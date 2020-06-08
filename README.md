# PHP GraphQL Request Factory

[![Build Status](https://github.com/simPod/PHP-GraphQL-Request-Factory/workflows/CI/badge.svg?branch=master)](https://github.com/simPod/PHP-GraphQL-Request-Factory/actions)
[![Coverage Status](https://coveralls.io/repos/github/simPod/PHP-GraphQL-Request-Factory/badge.svg?branch=master)](https://coveralls.io/github/simPod/PHP-GraphQL-Request-Factory?branch=master)
[![Downloads](https://poser.pugx.org/simpod/graphql-request-factory/d/total.svg)](https://packagist.org/packages/simpod/graphql-request-factory)
[![Packagist](https://poser.pugx.org/simpod/graphql-request-factory/v/stable.svg)](https://packagist.org/packages/simpod/graphql-request-factory)
[![Type Coverage](https://shepherd.dev/github/simPod/PHP-GraphQL-Request-Factory/coverage.svg)](https://shepherd.dev/github/simPod/PHP-GraphQL-Request-Factory)
[![Infection MSI](https://badge.stryker-mutator.io/github.com/simPod/PHP-GraphQL-Request-Factory/master)](https://infection.github.io)

This factory creates PSR-7 GraphQL Request through PSR-17 message factories
that you can be passed to PSR-18 client. 

## Installation

Add as [Composer](https://getcomposer.org/) dependency:

```sh
composer require simpod/graphql-request-factory
```

## Usage

```php
<?php

declare(strict_types=1);

namespace YourNs;

use SimPod\GraphQLRequestFactory\GraphQLRequestFactory;

$requestFactory = new GraphQLRequestFactory(new RequestFactoryInterfaceImpl(), new StreamFactoryInterfaceImpl());

$request = $requestFactory->createRequest(
    'https://localhost/graphql',
    <<<'GRAPHQL'
query GetHuman($id: ID!) {
  human(id: $id) {
    name
    appearsIn
    starships {
      name
    }
  }
}
GRAPHQL,
    ['id' => 1]
);
```

You can pass `$query` either as a `string` or PSR-7 `StreamInterface`.

### Mkay, but what should I do with the request then?

You can pass it to any HTTP client supporting PSR-7. It's up to you what client you decide to use.

#### Guzzle

http://docs.guzzlephp.org/en/stable/quickstart.html#sending-requests

```php
$response = $client->send($request);
```

#### PSR-18 Client (eg. HTTPPlug)

https://www.php-fig.org/psr/psr-18/#clientinterface

```php
$response = $client->sendRequest($request);
```
