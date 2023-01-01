<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types = 1);

/**
 * @var string $title
 *   Human version of the file name.
 * @var string $php
 *   Original php snippet.
 * @var bool $fail
 *   TRUE if this example is expected to fail.
 */

use Donquixote\DID\Util\PhpUtil;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;

try {
  $expression = eval($php);
  if ($fail) {
    Assert::fail('Expected an exception.');
  }
  $statement = 'return ' . $expression . ';';
}
catch (AssertionFailedError $e) {
  throw $e;
}
catch (\Throwable $e) {
  if (!$fail) {
    throw $e;
  }
  $expression = PhpUtil::phpConstruct(get_class($e), [
    var_export($e->getMessage(), TRUE),
  ]);
}


?>
## <?=$title . "\n" ?>

Executed PHP:

```php
<?=$php . "\n" ?>
```

<?= $fail ? 'Exception' : 'Generated code' ?>:

```php
<?=PhpUtil::formatAsSnippet('throw ' . $expression . ';') . "\n" ?>
```
