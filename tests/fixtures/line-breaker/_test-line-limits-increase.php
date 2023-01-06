<?php

declare(strict_types = 1);

/**
 * @var string $php
 *   Original php snippet.
 */

use Donquixote\DID\CodegenTools\LineBreaker;
use PHPUnit\Framework\Assert;

$lineBreaker = new LineBreaker();

$prettyPhp_1 = $lineBreaker->withMaxLineLength(1)->breakLongLines($php);

$prettyPhp_1_40 = $lineBreaker->withMaxLineLength(40)->breakLongLines($prettyPhp_1);
$prettyPhp_40 = $lineBreaker->withMaxLineLength(40)->breakLongLines($php);

Assert::assertSame($prettyPhp_1_40, $prettyPhp_40);

$prettyPhp_1_40_999 = $lineBreaker->withMaxLineLength(999)->breakLongLines($prettyPhp_1_40);
$prettyPhp_999 = $lineBreaker->withMaxLineLength(999)->breakLongLines($php);

Assert::assertSame($prettyPhp_1_40_999, $prettyPhp_999);
