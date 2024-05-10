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

$filePhp = CodeFormatUtil::formatAsFile($php);

if (!str_ends_with($filePhp, "\n")) {
  $filePhp .= "\n# MISSING LINE BREAK";
}
else {
  // Don't print the trailing line break in markdown.
  $filePhp = substr($filePhp, 0, -1);
}

print TestUtil::formatMarkdownSection(
  'Formatted as file',
  $filePhp,
);
