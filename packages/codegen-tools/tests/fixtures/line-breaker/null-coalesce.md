## Null coalesce

Original php code:

```php
use Ock\CodegenTools\Tests\Fixtures\GenericObject;

return GenericObject::f() ?? GenericObject::g('first text if true', 'second text if true', ['third text which is wrapped in an array']);
```

Prettified code:

```php
use Ock\CodegenTools\Tests\Fixtures\GenericObject;

return GenericObject::f() ?? GenericObject::g(
  'first text if true',
  'second text if true',
  ['third text which is wrapped in an array'],
);
```
