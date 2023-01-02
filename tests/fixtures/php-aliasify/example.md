## Example

Original code:

```php
function f(\Aa\Bb $arg): \Aa\Dd {
  return new \Aa\Dd();
}
```

Aliasified code:

```php
use Aa\Bb;
use Aa\Dd;

function f(Bb $arg): Dd {
  return new Dd();
}
```
