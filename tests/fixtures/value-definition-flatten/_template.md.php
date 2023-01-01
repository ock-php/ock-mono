<?php

declare(strict_types = 1);

/**
 * @var string $title
 *   Human version of the file name.
 * @var string $php
 *   Original php snippet.
 */

use Donquixote\DID\Generator\Generator;
use Donquixote\DID\Util\PhpUtil;
use Donquixote\DID\ValueDefinitionProcessor\ValueDefinitionProcessor_FlatServiceDefinition;

$definition = eval($php);
$generator = new Generator();

$expression = $generator->generate($definition);
$snippet = PhpUtil::formatExpressionAsSnippet($expression);

$processor = new ValueDefinitionProcessor_FlatServiceDefinition();
$flatDefinition = $processor->process($definition);
$flatExpression = $generator->generate($flatDefinition);
$flatSnippet = PhpUtil::formatExpressionAsSnippet($flatExpression);

?>
## <?=$title . "\n" ?>

Value definition:

```php
<?=$php . "\n" ?>
```

Generated code:

```php
<?=$snippet . "\n"  ?>
```

<?php if ($definition === $flatDefinition): ?>
The flattened definition is identical.
<?php else: ?>
Flattened definition generated code:

```php
<?=$flatSnippet . "\n"  ?>
```
<?php endif; ?>
