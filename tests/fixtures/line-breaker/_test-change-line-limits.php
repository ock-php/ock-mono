<?php

declare(strict_types = 1);

/**
 * @var string $php
 *   Original php snippet.
 */

use Donquixote\DID\CodegenTools\LineBreaker;
use PHPUnit\Framework\Assert;

$lineBreaker = new LineBreaker();

$prettyPhp1 = $lineBreaker->withMaxLineLength(1)->breakLongLines($php);

$prettyPhp1 = $lineBreaker->withMaxLineLength(40)->breakLongLines($prettyPhp1);
$prettyPhp2 = $lineBreaker->withMaxLineLength(40)->breakLongLines($php);

Assert::assertSame($prettyPhp1, $prettyPhp2);
