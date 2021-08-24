<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Generator;

use Donquixote\ObCK\Formula\DefaultConf\Formula_DefaultConfInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;

/**
 * @see \Donquixote\ObCK\Formula\DefaultConf\Formula_DefaultConfInterface
 */
class Generator_DefaultConf implements GeneratorInterface {

  /**
   * @var \Donquixote\ObCK\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $defaultConf;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\DefaultConf\Formula_DefaultConfInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function create(
    Formula_DefaultConfInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?self {

    $decorated = Generator::fromFormula(
      $formula->getDecorated(),
      $formulaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    return new self(
      $decorated,
      $formula->getDefaultConf());
  }

  /**
   * @param \Donquixote\ObCK\Generator\GeneratorInterface $decorated
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
