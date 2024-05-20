<?php

declare(strict_types = 1);

/**
 * @var string $name
 *   Name of the markdown file.
 * @var string $php
 *   Original php snippet.
 */

use Ock\CodegenTools\LineBreaker;
use PHPUnit\Framework\Assert;

if (str_contains($name, 'irreversible')) {
  Assert::assertTrue(true);
  return;
}

$lineBreaker = new LineBreaker();

$prettyPhp_1 = $lineBreaker->withMaxLineLength(1)->breakLongLines($php);

$prettyPhp_1_40 = $lineBreaker->withMaxLineLength(40)->breakLongLines($prettyPhp_1);
$prettyPhp_40 = $lineBreaker->withMaxLineLength(40)->breakLongLines($php);

Assert::assertSame($prettyPhp_1_40, $prettyPhp_40);

$prettyPhp_1_40_999 = $lineBreaker->withMaxLineLength(999)->breakLongLines($prettyPhp_1_40);
$prettyPhp_999 = $lineBreaker->withMaxLineLength(999)->breakLongLines($php);

Assert::assertSame($prettyPhp_1_40_999, $prettyPhp_999);
