## Construct call 2

Original php code:

```php
use Donquixote\CodegenTools\Tests\Fixtures\GenericObject;

return new GenericObject(strtolower('This string will be lowercased to be less intimidating.'), strtoupper('This string will be uppercased to be more imposing.'));
```

Prettified code:

```php
use Donquixote\CodegenTools\Tests\Fixtures\GenericObject;

return new GenericObject(
  strtolower('This string will be lowercased to be less intimidating.'),
  strtoupper('This string will be uppercased to be more imposing.'),
);
```
