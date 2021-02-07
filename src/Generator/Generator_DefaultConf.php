<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Formula\DefaultConf\Formula_DefaultConfInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

/**
 * @see \Donquixote\OCUI\Formula\DefaultConf\Formula_DefaultConfInterface
 */
class Generator_DefaultConf implements GeneratorInterface {

  /**
   * @var \Donquixote\OCUI\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $defaultConf;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\DefaultConf\Formula_DefaultConfInterface $schema
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function create(
    Formula_DefaultConfInterface $schema,
    FormulaToAnythingInterface $schemaToAnything
  ): ?self {

    $decorated = Generator::fromFormula(
      $schema->getDecorated(),
      $schemaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    return new self(
      $decorated,
      $schema->getDefaultConf());
  }

  /**
   * @param \Donquixote\OCUI\Generator\GeneratorInterface $decorated
   * @param mixed $defaultConf
   */
  public function __construct(GeneratorInterface $decorated, $defaultConf) {
    $this->decorated = $decorated;
    $this->defaultConf = $defaultConf;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if (NULL === $conf) {
      $conf = $this->defaultConf;
    }

    return $this->decorated->confGetPhp($conf);
  }
}
