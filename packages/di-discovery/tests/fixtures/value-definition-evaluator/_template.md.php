<?php

/**
 * @var string $title
 *   Human version of the file name.
 * @var string $php
 *   Original php snippet.
 *
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types = 1);

use Ock\DID\Tests\Util\TestUtil;

$definition = eval($php);
$evaluator = TestUtil::createDummyEvaluator();
$value = $evaluator->evaluate($definition);

$valueSerialized = serialize($value);
$phpSerialized = var_export($valueSerialized, TRUE);

?>
## <?=$title . "\n" ?>

Value definition:

```php
<?=$php . "\n" ?>
```

Serialized value:

```php
return unserialize(<?=$phpSerialized?>);
```
