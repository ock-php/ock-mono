<?php

declare(strict_types = 1);

/**
 * @var string $php
 *   Original php snippet.
 */

use Donquixote\CodegenTools\Util\CodeFormatUtil;
use Donquixote\CodegenTools\ValueExporter;

$value = eval($php);
$php = (new ValueExporter())->export($value);



?>

Exported value:

```php
<?= CodeFormatUtil::formatExpressionAsSnippet($php) . "\n" ?>
```
