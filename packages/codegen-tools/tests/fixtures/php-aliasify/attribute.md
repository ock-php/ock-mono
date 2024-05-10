## Attribute

Original code:

```php
#[\N\C(), \N\D, \N\E(\N\X, \N\D::class)]
#[\M\C]
function f() {}
```

Aliasified code:

```php
use M\C as C_0;
use N\C;
use N\D;
use N\E;
use const N\X;

#[C(), D, E(X, D::class)]
#[C_0]
function f() {}
```
