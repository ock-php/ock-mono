## For loop

Original php code:

```php
for ($i = 0; $i < 2; ++$i) {$x = 5;}
for ($i = 0; $i < 4; ++$i) {
  $x = 5;
}
for ($i = 0; $i < 5; ++$i) {$x = 5;
  $x = 7;
}
for ($i = 0; $i < 6; ++$i) {
  $x = 5; $x = 7;}
for (;;) {break;} 
// Return something so that the test has something to do.
return $i;
```

Prettified code:

```php
for ($i = 0; $i < 2; ++$i) {$x = 5;}
for ($i = 0; $i < 4; ++$i) {
  $x = 5;
}
for ($i = 0; $i < 5; ++$i) {$x = 5;
  $x = 7;
}
for ($i = 0; $i < 6; ++$i) {
  $x = 5; $x = 7;
}
for (;;) {break;}
// Return something so that the test has something to do.
return $i;
```
