[![Build Status](https://secure.travis-ci.org/donquixote/adaptism.png)](https://travis-ci.org/donquixote/adaptism)
[![Coverage Status](https://coveralls.io/repos/donquixote/adaptism/badge.png)](https://coveralls.io/r/donquixote/adaptism)

# Adaptism
Adaptism is a package that finds adapters for objects using a dispatch map.

Adapters can be annotated with [attributes](src/Attribute/Adapter.php) for discovery.

## Convert objects

```php
use Donquixote\Adaptism\Tests\Fixtures\Color\Hex\HexColorInterface;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColor;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

function f(UniversalAdapterInterface $universalAdapter): void {
  $rgb = new RgbColor(255, 0, 0);
  $hex = $universalAdapter->adapt($rgb, HexColorInterface::class);

  assert($hex instanceof HexColorInterface);
  assert($hex->getHexCode() === 'ff0000');
}
```

## Declare adapters

```php
use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Tests\Fixtures\Color\Hex\HexColorInterface;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColor;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface;

class C {
  #[Adapter]
  public static function adapt(
    #[Adaptee] RgbColorInterface $rgb,
  ): HexColorInterface {
    return new RgbColor(
      sprintf(
        '%02x%02x%02x',
        $rgbColor->red(), $rgbColor->green(), $rgbColor->blue()));
  }
}
```

## More examples?

Study [the tests](tests/src/AdapterTest.php)!
