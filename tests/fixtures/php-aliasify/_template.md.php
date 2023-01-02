<?php

declare(strict_types = 1);

/**
 * @var string $title
 *   Human version of the file name.
 * @var string $php
 *   Original php snippet.
 */

use Donquixote\DID\CodegenTools\Aliasifier;

$orig = $php;
$importsPhp = (new Aliasifier())->aliasify($php)->getImportsPhp();
$actual = $importsPhp . $php;

?>
## <?=$title . "\n" ?>

Original code:

```php
<?=$orig . "\n" ?>
```

Aliasified code:

```php
<?=$actual . "\n" ?>
```
