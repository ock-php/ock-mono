## Call multiline 2

Original php code:

```php
use Ock\CodegenTools\Tests\Fixtures\GenericObject;

return GenericObject::foo(
  ['op' => 'array', 'array' => [6, 7]],
);
```

Prettified code:

```php
use Ock\CodegenTools\Tests\Fixtures\GenericObject;

return GenericObject::foo(['op' => 'array', 'array' => [6, 7]]);
```
