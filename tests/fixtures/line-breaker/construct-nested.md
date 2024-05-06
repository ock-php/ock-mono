## Construct nested

Original php code:

```php
use Donquixote\CodegenTools\Tests\Fixtures\C;
use Donquixote\CodegenTools\Tests\Fixtures\ClassWithLongName;
use Donquixote\CodegenTools\Tests\Fixtures\ClassTheNameOfWhichIsEvenLonger;

$short = new C(new C(new C('abc')));

$long = new ClassWithLongName(new ClassWithLongName('abc'));

$longer = new ClassTheNameOfWhichIsEvenLonger(new ClassTheNameOfWhichIsEvenLonger('abc'));

$complex1 = new ClassWithLongName(
  new ClassWithLongName(
        new ClassWithLongName(5),
      new ClassWithLongName(1),
  ),
  new ClassWithLongName(2),
);

$complex2 = new ClassWithLongName(
  new ClassWithLongName(2),
  new ClassWithLongName(
    new ClassWithLongName(5),
    new ClassWithLongName(1),
  ),
);
```

Prettified code:

```php
use Donquixote\CodegenTools\Tests\Fixtures\C;
use Donquixote\CodegenTools\Tests\Fixtures\ClassWithLongName;
use Donquixote\CodegenTools\Tests\Fixtures\ClassTheNameOfWhichIsEvenLonger;

$short = new C(new C(new C('abc')));

$long = new ClassWithLongName(new ClassWithLongName('abc'));

$longer = new ClassTheNameOfWhichIsEvenLonger(
  new ClassTheNameOfWhichIsEvenLonger('abc'),
);

$complex1 = new ClassWithLongName(new ClassWithLongName(
  new ClassWithLongName(5),
  new ClassWithLongName(1),
), new ClassWithLongName(2));

$complex2 = new ClassWithLongName(
  new ClassWithLongName(2),
  new ClassWithLongName(new ClassWithLongName(5), new ClassWithLongName(1)),
);
```
