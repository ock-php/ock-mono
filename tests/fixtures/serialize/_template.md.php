<?php

declare(strict_types = 1);

/**
 * @var string $title
 *   Human version of the file name.
 * @var string $php
 *   Original php snippet.
 */

$value = eval($php);
$phpSerialized = var_export(serialize($value), TRUE);

?>
## <?=$title . "\n" ?>

Value:

```php
<?=$php . "\n" ?>
```

Serialized value:

```php
return unserialize(<?=$phpSerialized ?>);
```
