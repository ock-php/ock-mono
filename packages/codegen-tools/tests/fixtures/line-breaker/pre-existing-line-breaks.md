## Pre existing line breaks

Original php code:

```php
use Donquixote\CodegenTools\Tests\Fixtures\C;

return new C('long text in first argument',
 
 'long text in second argument', ['long text in array argument'
 ], [
 'first text in array', 
    'second text in array', 'third text in array'
    ], [
    5], substr('hello world',
    
    5)
    
    );
```

Prettified code:

```php
use Donquixote\CodegenTools\Tests\Fixtures\C;

return new C(
  'long text in first argument',
  'long text in second argument',
  ['long text in array argument'],
  ['first text in array', 'second text in array', 'third text in array'],
  [5],
  substr('hello world', 5),
);
```
