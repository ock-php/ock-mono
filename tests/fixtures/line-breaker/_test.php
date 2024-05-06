<?php

declare(strict_types = 1);

/**
 * @var string $php
 *   Original php snippet.
 */

use Donquixote\CodegenTools\LineBreaker;
use PHPUnit\Framework\Assert;

$prettyPhp = (new LineBreaker())->breakLongLines($php);

$origValue = eval($php);
try {
  $prettyValue = eval($prettyPhp);
}
catch (\Throwable $e) {
  Assert::fail(sprintf(
    '%s in pretty php: %s',
    get_class($e),
    $e->getMessage(),
  ));
}

Assert::assertSame(
  serialize(eval($php)),
  serialize(eval($prettyPhp)),
);
