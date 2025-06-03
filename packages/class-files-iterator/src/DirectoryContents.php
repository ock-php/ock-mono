<?php

declare(strict_types = 1);

namespace Ock\ClassFilesIterator;

/**
 * Value object with the result of a directory scan.
 */
final class DirectoryContents {

  /**
   * See http://php.net/manual/en/language.oop5.basic.php
   */
  private const CLASS_NAME_PATTERN = /** @lang RegExp */ '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';

  private const CLASS_FILE_PATTERN = /** @lang RegExp */ '/^([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\.php$/';

  /**
   * Constructor.
   *
   * @param list<string> $subdirNames
   * @param list<string> $fileNames
   */
  public function __construct(
    public readonly array $subdirNames,
    public readonly array $fileNames,
  ) {}

  /**
   * Static factory which loads a directory.
   *
   * @param string $directory
   *   Directory path without trailing slash.
   *
   * @return static
   */
  public static function load(string $directory): static {
    assert(!str_ends_with($directory, '/'));
    $iterator = new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS|\FilesystemIterator::KEY_AS_FILENAME|\FilesystemIterator::CURRENT_AS_SELF);
    $subdir_names = [];
    $file_names = [];
    foreach ($iterator as $name => $iterator_self) {
      if ($iterator->hasChildren()) {
        $subdir_names[] = $name;
      }
      else {
        $file_names[] = $name;
      }
    }
    sort($subdir_names);
    sort($file_names);
    return new static($subdir_names, $file_names);
  }

  /**
   * Gets file names and subdir names as a mixed sorted array.
   *
   * @return array<string, bool>
   */
  public function getFilesAndDirectoriesMap(): array {
    $map = array_fill_keys($this->subdirNames, true)
      + array_fill_keys($this->fileNames, false);
    ksort($map);
    return $map;
  }

  /**
   * Gets file names and subdir names as a mixed sorted array.
   *
   * @return \Iterator<string, bool>
   */
  public function iterateClassAndNamespaceMap(): \Iterator {
    foreach ($this->getFilesAndDirectoriesMap() as $candidate => $is_dir) {
      if ($is_dir) {
        if (preg_match(self::CLASS_NAME_PATTERN, $candidate)) {
          yield $candidate => TRUE;
        }
      }
      else {
        if (preg_match(self::CLASS_FILE_PATTERN, $candidate, $matches)) {
          yield $matches[1] => FALSE;
        }
      }
    }
  }

  /**
   * Gets class shortnames based on *.php file names.
   *
   * @return list<string>
   *   Class shortnames.
   */
  public function getClassNames(): array {
    $names = [];
    foreach ($this->fileNames as $file_name) {
      if (preg_match(self::CLASS_FILE_PATTERN, $file_name, $matches)) {
        $names[] = $matches[1];
      }
    }
    return $names;
  }

  /**
   * Gets namespace shortnames based on subdirectory names.
   *
   * @return list<string>
   *   Namespace shortnames.
   */
  public function getNamespaceNames(): array {
    $names = preg_grep(self::CLASS_NAME_PATTERN, $this->subdirNames);
    assert($names !== false);
    return $names;
  }

}
