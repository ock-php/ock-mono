<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;

class Generator_Optional implements GeneratorInterface {

  /**
   * @var \Donquixote\OCUI\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface
   */
  private $schema;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\OCUI\Generator\GeneratorInterface|null
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  public static function create(Formula_OptionalInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?GeneratorInterface {

    $decorated = Generator::fromSchema($schema->getDecorated(), $schemaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    return new self(
      $decorated,
      $schema);
  }

  /**
   * @param \Donquixote\OCUI\Generator\GeneratorInterface $decorated
   * @param \Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface $schema
   */
  public function __construct(GeneratorInterface $decorated, Formula_OptionalInterface $schema) {
    $this->decorated = $decorated;
    $this->schema = $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if (!\is_array($conf) || empty($conf['enabled'])) {
      return $this->schema->getEmptyPhp();
    }

    $subConf = $conf['options'] ?? null;

    return $this->decorated->confGetPhp($subConf);
  }
}
