## Ternary call

Original php code:

```php
use Ock\CodegenTools\Tests\Fixtures\GenericObject;

return GenericObject::f() ? GenericObject::g('first text if true', 'second text if true', ['third text which is wrapped in an array']) : ['some key' => new GenericObject('argument with text',
  'second argument with more text which is longer than the previous')];
```

Prettified code:

```php
use Ock\CodegenTools\Tests\Fixtures\GenericObject;

return GenericObject::f()
  ? GenericObject::g(
    'first text if true',
    'second text if true',
    ['third text which is wrapped in an array'],
  )
  : ['some key' => new GenericObject(
    'argument with text',
    'second argument with more text which is longer than the previous',
  )];
```
