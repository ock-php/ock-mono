<?php

declare(strict_types = 1);

/**
 * @var string $php
 *   Original php snippet.
 */

use Donquixote\DID\CodegenTools\LineBreaker;
use PHPUnit\Framework\Assert;

$lineBreaker = (new LineBreaker())->withMaxLineLength(7);
$prettyPhp = $lineBreaker->breakLongLines($php);
$prettyPhp2 = $lineBreaker->breakLongLines($prettyPhp);

Assert::assertSame($prettyPhp, $prettyPhp2);
