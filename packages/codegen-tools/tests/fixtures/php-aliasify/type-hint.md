## Type hint

Original code:

```php
function f(\N\C $y = \N\X, \N\C|\N\D $y): \N\E {
  return new \N\C(\N\g(\N\U));
}
```

Aliasified code:

```php
use N\C;
use N\D;
use N\E;
use function N\g;
use const N\U;
use const N\X;

function f(C $y = X, C|D $y): E {
  return new C(g(U));
}
```
