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
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public function schema(
    CfSchemaInterface $schema,
    string $interface,
    SchemaToAnythingInterface $helper
  ): ?object {
    static $recursionLevel = 0;
    ++$recursionLevel;

    if (NULL === $replacement = $this->replacer->schemaGetReplacement($schema)) {
      --$recursionLevel;
      return NULL;
    }

    if ($replacement === $schema) {
      --$recursionLevel;
      throw new SchemaToAnythingException("Replacer did not replace.");
    }

    if ($recursionLevel > 10) {
      # kdpm($schema, spl_object_hash($schema));
      # kdpm($replacement, spl_object_hash($replacement));
      # kdpm($this->replacer, 'REPLACER');
      --$recursionLevel;
      throw new SchemaToAnythingException("Recursion.");
    }

    /** @noinspection SuspiciousBinaryOperationInspection */
    if (false && \get_class($replacement) === \get_class($schema)) {
      # kdpm($schema, spl_object_hash($schema));
      # kdpm($replacement, spl_object_hash($replacement));
      # kdpm($this->replacer, 'REPLACER');
      --$recursionLevel;
      throw new SchemaToAnythingException("Replacer did not replace.");
    }

    $anything = $helper->schema($replacement, $interface);

    # if (NULL === $anything) {
      # kdpm($replacement, 'REPLACEMENT DID NOT HELP');
    # }

    --$recursionLevel;
    return $anything;
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
