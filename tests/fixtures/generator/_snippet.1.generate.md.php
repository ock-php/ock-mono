<?php

declare(strict_types = 1);

/**
 * @var string $php
 *   Original php snippet.
 */

use Donquixote\DID\Generator\Generator;
use Donquixote\DID\Util\PhpUtil;

$definition = eval($php);
$expression = (new Generator())->generate($definition);
/** @noinspection PhpUnhandledExceptionInspection */
$snippet = PhpUtil::formatAsSnippet('return ' . $expression . ';');

?>

Generated code:

```php
<?= $snippet . "\n" ?>
```
