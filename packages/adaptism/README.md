[![Build Status](https://secure.travis-ci.org/ock/adaptism.png)](https://travis-ci.org/ock/adaptism)
[![Coverage Status](https://coveralls.io/repos/ock/adaptism/badge.png)](https://coveralls.io/r/ock/adaptism)

# Adaptism
Adaptism is a package that finds adapters for objects using a dispatch map.

Adapters can be annotated with [attributes](src/Attribute/Adapter.php) for discovery.

## Convert objects

```php
use Ock\Adaptism\Tests\Fixtures\Color\Hex\HexColorInterface;
use Ock\Adaptism\Tests\Fixtures\Color\Rgb\RgbColor;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;

function f(UniversalAdapterInterface $universalAdapter): void {
  $rgb = new RgbColor(255, 0, 0);
  $hex = $universalAdapter->adapt($rgb, HexColorInterface::class);

  assert($hex instanceof HexColorInterface);
  assert($hex->getHexCode() === 'ff0000');
}
```

## Declare adapters

```php
use Ock\Adaptism\Attribute\Adapter;
use Ock\Adaptism\Attribute\Parameter\Adaptee;
use Ock\Adaptism\Tests\Fixtures\Color\Hex\HexColorInterface;
use Ock\Adaptism\Tests\Fixtures\Color\Rgb\RgbColor;
use Ock\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface;

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
