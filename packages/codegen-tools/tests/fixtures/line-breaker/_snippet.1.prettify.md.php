<?php

declare(strict_types = 1);

/**
 * @var string $php
 *   Original php snippet.
 */

use Ock\CodegenTools\LineBreaker;

$prettyPhp = (new LineBreaker())->breakLongLines($php);

?>

Prettified code:

```php
<?= $prettyPhp . "\n" ?>
```
