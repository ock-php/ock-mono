<?php

declare(strict_types = 1);

/**
 * @var string $title
 *   Human version of the file name.
 * @var string $php
 *   Original php snippet.
 */

use Donquixote\DID\Util\PhpUtil;

$orig = $php;
$aliases = PhpUtil::aliasify($php);
$aliases_php = PhpUtil::formatAliases($aliases);
$actual = $aliases_php . "\n" . $php;

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
