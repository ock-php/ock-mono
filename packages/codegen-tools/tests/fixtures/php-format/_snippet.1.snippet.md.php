<?php

declare(strict_types = 1);

/**
 * @var string $title
 *   Human version of the file name.
 * @var string $php
 *   Original php snippet.
 * @var bool $fail
 *   TRUE if this example is expected to fail.
 */

use Ock\CodegenTools\Tests\Util\TestUtil;
use Ock\CodegenTools\Util\CodeFormatUtil;

print "\n";

print TestUtil::formatMarkdownSection(
  'Formatted as snippet',
  CodeFormatUtil::formatAsSnippet($php),
);
