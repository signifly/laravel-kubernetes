# Integration helpers for running services in Kubernetes

[![Latest Version on Packagist](https://img.shields.io/packagist/v/signifly/laravel-kubernetes.svg?style=flat-square)](https://packagist.org/packages/signifly/laravel-kubernetes)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/signifly/laravel-kubernetes/run-tests?label=tests)](https://github.com/signifly/laravel-kubernetes/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/signifly/laravel-kubernetes.svg?style=flat-square)](https://packagist.org/packages/signifly/laravel-kubernetes)


This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require signifly/laravel-kubernetes
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Signifly\Kubernetes\KubernetesServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Signifly\Kubernetes\KubernetesServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

``` php
$laravel-kubernetes = new Signifly\Kubernetes();
echo $laravel-kubernetes->echoPhrase('Hello, Signifly!');
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Matthias Larsen](https://github.com/connors511)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
