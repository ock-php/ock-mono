## For loop.irreversible

Original php code:

```php
for ($i = 0; $i < 3; ++$i) {$x = '' . 'very long string which is too long for a single line';}
// Return something so that the test has something to do.
return $i;
```

Prettified code:

```php
for ($i = 0; $i < 3; ++$i) {$x = ''
  . 'very long string which is too long for a single line';
}
// Return something so that the test has something to do.
return $i;
```
