<?php

declare(strict_types = 1);

/**
 * @var string $title
 *   Human version of the file name.
 * @var string $php
 *   Original php snippet.
 * @var bool $fail
 *   TRUE if this example is expected to fail.
 */

use Donquixote\DID\Generator\Generator;
use Donquixote\DID\Util\PhpUtil;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;

$orig = $php;
$definition = eval($php);
$generator = new Generator();

try {
  $expression = $generator->generate($definition);
  if ($fail) {
    Assert::fail('Expected an exception.');
  }
  $statement = 'return ' . $expression . ';';
}
catch (AssertionFailedError $e) {
  throw $e;
}
catch (\Exception $e) {
  if (!$fail) {
    throw $e;
  }
  $expression = PhpUtil::phpConstruct(get_class($e), [
    var_export($e->getMessage(), TRUE),
  ]);
  $statement = 'throw ' . $expression . ';';
}

?>
## <?=$title . "\n" ?>

Value definition:

```php
<?=$orig . "\n" ?>
```

<?= $fail ? 'Exception' : 'Generated code' ?>:

```php
<?=PhpUtil::formatAsSnippet($statement) . "\n" ?>
```
