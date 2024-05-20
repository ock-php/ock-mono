## Pre existing trailing comma

Original php code:

```php
use Ock\CodegenTools\Tests\Fixtures\C;

return new C('long text in first argument', 'long text in second argument', ['long text in array argument',], ['first text in array', 'second text in array', 'third text in array'], [5,], substr('banana', 2,));
```

Prettified code:

```php
use Ock\CodegenTools\Tests\Fixtures\C;

return new C(
  'long text in first argument',
  'long text in second argument',
  ['long text in array argument'],
  ['first text in array', 'second text in array', 'third text in array'],
  [5],
  substr('banana', 2),
);
```
