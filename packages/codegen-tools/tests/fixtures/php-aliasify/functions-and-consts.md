## Functions and consts

Original code:

```php
new \N\C();
\N\f(\N\X);
\g(\Y);
```

Aliasified code:

```php
use N\C;
use function N\f;
use const N\X;

new C();
f(X);
\g(\Y);
```
