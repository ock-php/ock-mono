## Match

Original php code:

```php
return match (5) {
    'key 0' => 'value 0',
    'key 1' => 'value 1',
    'key 2' => 'value 2',
    'key 3' => 'value 3', 
    'key 4' => 'value 4',
    default => 5,
  };
```

Prettified code:

```php
return match (5) {
  'key 0' => 'value 0',
  'key 1' => 'value 1',
  'key 2' => 'value 2',
  'key 3' => 'value 3',
  'key 4' => 'value 4',
  default => 5,
};
```
