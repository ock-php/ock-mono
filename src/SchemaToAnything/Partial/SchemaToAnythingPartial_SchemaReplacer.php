<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaToAnything\Partial;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Exception\SchemaToAnythingException;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

class SchemaToAnythingPartial_SchemaReplacer implements SchemaToAnythingPartialInterface {

  /**
   * @var \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface
   */
  private $replacer;

  /**
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   */
  public function __construct(SchemaReplacerInterface $replacer) {
    $this->replacer = $replacer;
  }

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function schema(
    CfSchemaInterface $schema,
    string $interface,
    SchemaToAnythingInterface $helper
  ): ?object {
    static $recursionLevel = 0;

    if ($recursionLevel > 10) {
      throw new SchemaToAnythingException("Recursion in schema replacer.");
    }

    ++$recursionLevel;
    // Use try/finally to make sure that recursion level will be decremented.
    try {
      $replacement = $this->replacer->schemaGetReplacement($schema);

      if ($replacement === NULL) {
        return NULL;
      }

      if ($replacement === $schema) {
        throw new SchemaToAnythingException("Replacer did not replace. Replacement is identical.");
      }

      if (\get_class($replacement) === \get_class($schema)) {
        throw new SchemaToAnythingException("Replacer did not replace. Replacement has same class.");
      }

      return $helper->schema($replacement, $interface);
    }
    finally {
      --$recursionLevel;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function providesResultType(string $resultInterface): bool {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function acceptsSchemaClass(string $schemaClass): bool {
    return $this->replacer->acceptsSchemaClass($schemaClass);
  }
}
