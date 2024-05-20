<?php

declare(strict_types = 1);

/**
 * @var string $title
 *   Human version of the file name.
 * @var string $php
 *   Original php snippet.
 */

use Ock\CodegenTools\Aliasifier;

$importsPhp = (new Aliasifier())->aliasify($php)->getImportsPhp();
$actual = $importsPhp . $php;

?>

Aliasified code:

```php
<?=$actual . "\n" ?>
```
