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

use Donquixote\CodegenTools\Tests\Util\TestUtil;
use Donquixote\CodegenTools\Util\CodeFormatUtil;
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
