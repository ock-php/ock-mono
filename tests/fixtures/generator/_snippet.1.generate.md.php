<?php

declare(strict_types = 1);

/**
 * @var string $php
 *   Original php snippet.
 */

use Donquixote\DID\Generator\ValueDefinitionToPhp;
use Donquixote\DID\Util\PhpUtil;

$definition = eval($php);
$expression = (new ValueDefinitionToPhp())->generate($definition);
/** @noinspection PhpUnhandledExceptionInspection */
$snippet = PhpUtil::formatAsSnippet('return ' . $expression . ';');

?>

Generated code:

```php
<?= $snippet . "\n" ?>
```
