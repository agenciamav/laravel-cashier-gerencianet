<p align="center"><a href="https://laravel-cashier-gerencianet.netlify.app/" ><img src="https://raw.githubusercontent.com/agenciamav/laravel-cashier-gerencianet/master/art/cover.jpg" width="100%"></a></p>

<p align="center">
  
  [![Latest Version on Packagist](https://img.shields.io/packagist/v/agenciamav/laravel-cashier-gerencianet.svg?style=flat-square)](https://packagist.org/packages/agenciamav/laravel-cashier-gerencianet)
  [![Total Downloads](https://img.shields.io/packagist/dt/agenciamav/laravel-cashier-gerencianet.svg?style=flat-square)](https://packagist.org/packages/agenciamav/laravel-cashier-gerencianet)
  ![GitHub Actions](https://github.com/agenciamav/laravel-cashier-gerencianet/actions/workflows/main.yml/badge.svg)
  
</p>

[Documentação](https://laravel-cashier-gerencianet.netlify.app/)
[App de exemplo](https://github.com/agenciamav/laravel-cashier-gerencianet-example)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Testes / Desenvolvimento TDD

No diretório do pacote, execute o comando:
```shell
php ./vendor/bin/pest 
```
ou
```shell
composer run test 
```

```shell
λ composer run test
> vendor/bin/pest

   PASS  AgenciaMav\LaravelCashierGerencianet\Tests\Feature\ChargesTest
  ✓ customers can be creted
  ✓ billet
  ✓ cancel
  ✓ create
  ✓ detail
  ✓ one step billet
  ✓ one step billet marketplace

   PASS  AgenciaMav\LaravelCashierGerencianet\Tests\Feature\SubscriptionsTest
  ✓ customers can be creted
  ✓ cancel subscription
  ✓ create plan
  ✓ create subscription
  ✓ create subscription history
  ✓ delete plan
  ✓ detail subscription
  ✓ get plans

  Tests:  15 passed
  Time:   142.26s


```

### Security

If you discover any security related issues, please email tonetlds@gmail.com instead of using the issue tracker.

## Credits

- [L. Tonet](https://github.com/lucianotonet)
- [All Contributors](../../contributors)

## License

Under MIT license. Please see [License File](LICENSE.md) for more information.
