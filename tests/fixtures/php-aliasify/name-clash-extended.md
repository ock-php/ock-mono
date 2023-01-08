## Name clash extended

Original code:

```php
function f(\N\C|\M\C $y = \N\X + \M\X, \M\C|\N\D $y): \N\E {
  return new \N\C(\N\g(\N\U), \M\g(\M\U));
}
```

Aliasified code:

```php
use M\C as C_0;
use N\C;
use N\D;
use N\E;
use function M\g as g_0;
use function N\g;
use const M\U as U_0;
use const M\X as X_0;
use const N\U;
use const N\X;

function f(C|C_0 $y = X + X_0, C_0|D $y): E {
  return new C(g(U), g_0(U_0));
}
```
