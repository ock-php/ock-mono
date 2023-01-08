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

use Donquixote\CodegenTools\Util\CodeFormatUtil;
use Donquixote\DID\Tests\Util\TestUtil;
use Donquixote\CodegenTools\Util\CodeGen;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;

$orig = $php;


?>
## <?=$title . "\n" ?>

Original PHP:

```php
<?=$orig . "\n" ?>
```

<?php

try {
  print TestUtil::formatMarkdownSection(
    'Formatted as snippet',
    CodeFormatUtil::formatAsSnippet($php),
  );
  print "\n";
  print TestUtil::formatMarkdownSection(
    'Formatted as file',
    CodeFormatUtil::formatAsFile($php),
  );
  if ($fail) {
    Assert::fail('Expected an exception.');
  }
}
catch (AssertionFailedError $e) {
  throw $e;
}
catch (\Throwable $e) {
  if (!$fail) {
    throw $e;
  }
  $expression = CodeGen::phpConstruct(get_class($e), [
    var_export($e->getMessage(), TRUE),
  ]);
  print TestUtil::formatMarkdownSection(
    'Exception',
    CodeFormatUtil::formatAsSnippet('throw ' . $expression . ';'),
  );
}
