<?php

declare(strict_types = 1);

/**
 * @var string $php
 *   Original php snippet.
 */

use Ock\CodegenTools\LineBreaker;
use PHPUnit\Framework\Assert;

$lineBreaker = new LineBreaker();

$prettyPhp_999 = $lineBreaker->withMaxLineLength(999)->breakLongLines($php);

$prettyPhp_999_40 = $lineBreaker->withMaxLineLength(40)->breakLongLines($prettyPhp_999);
$prettyPhp_40 = $lineBreaker->withMaxLineLength(40)->breakLongLines($php);

Assert::assertSame($prettyPhp_999_40, $prettyPhp_40);

$prettyPhp_999_40_1 = $lineBreaker->withMaxLineLength(1)->breakLongLines($prettyPhp_999_40);
$prettyPhp_40_1 = $lineBreaker->withMaxLineLength(1)->breakLongLines($prettyPhp_40);
$prettyPhp_1 = $lineBreaker->withMaxLineLength(1)->breakLongLines($php);

Assert::assertSame($prettyPhp_999_40_1, $prettyPhp_1);
Assert::assertSame($prettyPhp_999_40_1, $prettyPhp_40_1);
