## Imports order

Original code:

```php
return [
  \Acme\ClassZ::class,
  \Acme\ClassA::class,
  \Acme\Class3::class,
  \Acme\ClasswithLowerCaseW::class,
  \Acme\ClassWithLongName::class,
  \Acme\Class_WithUnderscore::class,
  \A\me\B::class,
  \A\me\Z::class,
  \Acme\C::class,
];
```

Aliasified code:

```php
use A\me\B;
use A\me\Z;
use Acme\C;
use Acme\Class3;
use Acme\Class_WithUnderscore;
use Acme\ClassA;
use Acme\ClassWithLongName;
use Acme\ClasswithLowerCaseW;
use Acme\ClassZ;

return [
  ClassZ::class,
  ClassA::class,
  Class3::class,
  ClasswithLowerCaseW::class,
  ClassWithLongName::class,
  Class_WithUnderscore::class,
  B::class,
  Z::class,
  C::class,
];
```
