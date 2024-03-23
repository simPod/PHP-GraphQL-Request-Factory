# PHP GraphQL Request Factory

[![GitHub Actions][GA Image]][GA Link]
[![Code Coverage][Coverage Image]][CodeCov Link]
[![Downloads][Downloads Image]][Packagist Link]
[![Packagist][Packagist Image]][Packagist Link]
[![Infection MSI][Infection Image]][Infection Link]

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

[GA Image]: https://github.com/simPod/PHP-GraphQL-Request-Factory/workflows/Continuous%20Integration/badge.svg
[GA Link]: https://github.com/simPod/PHP-GraphQL-Request-Factory/actions?query=workflow%3A%22Continuous+Integration%22+branch%3Amaster
[Coverage Image]: https://codecov.io/gh/simPod/PHP-GraphQL-Request-Factory/branch/master/graph/badge.svg
[CodeCov Link]: https://codecov.io/gh/simPod/PHP-GraphQL-Request-Factory/branch/master
[Downloads Image]: https://poser.pugx.org/simpod/graphql-request-factory/d/total.svg
[Packagist Image]: https://poser.pugx.org/simpod/graphql-request-factory/v/stable.svg
[Packagist Link]: https://packagist.org/packages/simpod/graphql-request-factory
[Infection Image]: https://badge.stryker-mutator.io/github.com/simPod/PHP-GraphQL-Request-Factory/master
[Infection Link]: https://infection.github.io
