## Extends implements

Original code:

```php
class C extends \N\B implements \N\I, \N\J {

}
```

Aliasified code:

```php
use N\B;
use N\I;
use N\J;

class C extends B implements I, J {

}
```
