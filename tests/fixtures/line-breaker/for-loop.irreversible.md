## For loop.irreversible

Original php code:

```php
for ($i = 0; $i < 3; ++$i) {$x = '' . 'very long string which is too long for a single line';}
for ($i = 0; $i < 7; ++$i) {$x = 5; $x = 7
  * 3;}
// Return something so that the test has something to do.
return $i;
```

Prettified code:

```php
for ($i = 0; $i < 3; ++$i) {$x = ''
  . 'very long string which is too long for a single line';
}
for ($i = 0; $i < 7; ++$i) {$x = 5; $x = 7 * 3;}
// Return something so that the test has something to do.
return $i;
```
