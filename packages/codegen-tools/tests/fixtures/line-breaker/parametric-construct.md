## Parametric construct

Original php code:

```php
use Ock\CodegenTools\Tests\Fixtures\C;
use Ock\CodegenTools\Tests\Fixtures\GenericObject;

$container = new GenericObject();

return (static fn (...$args) => new C(
5, $container->get('some_service'), $args[0], $container->get('other_service')->foo($args[1])))('a', 'b');
```

Prettified code:

```php
use Ock\CodegenTools\Tests\Fixtures\C;
use Ock\CodegenTools\Tests\Fixtures\GenericObject;

$container = new GenericObject();

return (static fn (...$args) => new C(
  5,
  $container->get('some_service'),
  $args[0],
  $container->get('other_service')->foo($args[1]),
))('a', 'b');
```
