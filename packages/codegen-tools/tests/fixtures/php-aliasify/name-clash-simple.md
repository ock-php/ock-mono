## Name clash simple

Original code:

```php
\N\X + \M\X + \O\X;
new \N\C();
$x instanceof \M\C;
fn (\O\C $x) => null;
\N\f() + \M\f() + \O\f() + \P\P\f();
```

Aliasified code:

```php
use M\C as C_0;
use N\C;
use O\C as C_1;
use function M\f as f_0;
use function N\f;
use function O\f as f_1;
use function P\P\f as f_2;
use const M\X as X_0;
use const N\X;
use const O\X as X_1;

X + X_0 + X_1;
new C();
$x instanceof C_0;
fn (C_1 $x) => null;
f() + f_0() + f_1() + f_2();
```
