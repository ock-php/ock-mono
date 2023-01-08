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

use Donquixote\CodegenTools\Tests\Util\TestUtil;
use Donquixote\CodegenTools\Util\CodeFormatUtil;

print "\n";

print TestUtil::formatMarkdownSection(
  'Formatted as snippet',
  CodeFormatUtil::formatAsSnippet($php),
);
