<?php

declare(strict_types = 1);

/**
 * @var string $title
 *   Human version of the file name.
 * @var string $php
 *   Original php snippet.
 */

use Ock\CodegenTools\Util\CodeFormatUtil;
use Ock\DID\ValueDefinitionProcessor\ValueDefinitionProcessor_FlatServiceDefinition;
use Ock\DID\ValueDefinitionToPhp\ValueDefinitionToPhp;

$definition = eval($php);
$generator = new ValueDefinitionToPhp();

$expression = $generator->generate($definition);
$snippet = CodeFormatUtil::formatExpressionAsSnippet($expression);

$processor = new ValueDefinitionProcessor_FlatServiceDefinition();
$flatDefinition = $processor->process($definition);
$flatExpression = $generator->generate($flatDefinition);
$flatSnippet = CodeFormatUtil::formatExpressionAsSnippet($flatExpression);

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
