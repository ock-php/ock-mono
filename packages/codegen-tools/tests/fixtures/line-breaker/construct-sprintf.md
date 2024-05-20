## Construct sprintf

Original php code:

```php
use Ock\CodegenTools\Tests\Fixtures\GenericObject;

return new GenericObject(sprintf('Expected %d coconuts, but found only %d. Please provide more coconuts.', 99, 3));
```

Prettified code:

```php
use Ock\CodegenTools\Tests\Fixtures\GenericObject;

return new GenericObject(sprintf(
  'Expected %d coconuts, but found only %d. Please provide more coconuts.',
  99,
  3,
));
```
