## Construct

Value:

```php
use Ock\CodegenTools\Tests\Fixtures\C;use Ock\CodegenTools\Tests\Fixtures\ExampleExportable;

return [
  new C(5, 'eight'),
  new \ReflectionClass(C::class),
  new \ReflectionFunction('strtolower'),
  new \ReflectionMethod(C::class, 'foo'),
  new \ReflectionMethod(new C(), 'foo'),
  new \ReflectionParameter('strtolower', 'string'),
  new \ReflectionParameter('strtolower', 0),
  new \ReflectionParameter([C::class, 'foo'], 0),
  new ExampleExportable(5, 'hello', new C()),
];
```

Exported value:

```php
use Ock\CodegenTools\Tests\Fixtures\C;
use Ock\CodegenTools\Tests\Fixtures\ExampleExportable;

return [
  \unserialize(
    'O:33:"Ock\\CodegenTools\\Tests\\Fixtures\\C":1:{s:6:"values";a:2:{i:0;i:5;i:1;s:5:"eight";}}',
  ),
  new \ReflectionClass(C::class),
  new \ReflectionFunction('strtolower'),
  new \ReflectionMethod(C::class, 'foo'),
  new \ReflectionMethod(C::class, 'foo'),
  new \ReflectionParameter('strtolower', 0),
  new \ReflectionParameter('strtolower', 0),
  new \ReflectionParameter([C::class, 'foo'], 0),
  new ExampleExportable(5, 'hello', \unserialize(
    'O:33:"Ock\\CodegenTools\\Tests\\Fixtures\\C":1:{s:6:"values";a:0:{}}',
  )),
];
```
