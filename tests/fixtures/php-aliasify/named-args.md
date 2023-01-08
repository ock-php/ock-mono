## Named args

Original code:

```php
f(arg: new \Aa\Bb());
```

Aliasified code:

```php
use Aa\Bb;

f(arg: new Bb());
```
