# PHP GraphQL Request Factory

[![Build Status](https://travis-ci.com/simPod/GraphQL-Request-Factory.svg?branch=master)](https://travis-ci.com/simPod/GraphQL-Request-Factory)
[![Downloads](https://poser.pugx.org/simpod/graphql-request-factory/d/total.svg)](https://packagist.org/packages/simpod/graphql-request-factory)
[![Packagist](https://poser.pugx.org/simpod/graphql-request-factory/v/stable.svg)](https://packagist.org/packages/simpod/graphql-request-factory)
[![Licence](https://poser.pugx.org/simpod/graphql-request-factory/license.svg)](https://packagist.org/packages/simpod/graphql-request-factory)
[![Quality Score](https://scrutinizer-ci.com/g/simPod/GraphQL-Request-Factory/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/simPod/GraphQL-Request-Factory)
[![Code Coverage](https://scrutinizer-ci.com/g/simPod/GraphQL-Request-Factory/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/simPod/GraphQL-Request-Factory)

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
