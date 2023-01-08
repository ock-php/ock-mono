## Array nested multiline

Original php code:

```php
return ['a', ['x' => 'People say that drinking water is important', 'y' => 'Y'], 'but is it?', 'c', [[]]];
```

Prettified code:

```php
return [
  'a',
  ['x' => 'People say that drinking water is important', 'y' => 'Y'],
  'but is it?',
  'c',
  [[]],
];
```
