<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

class Generator_Optional implements GeneratorInterface {

  /**
   * @var \Donquixote\OCUI\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface
   */
  private $formula;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\OCUI\Generator\GeneratorInterface|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function create(Formula_OptionalInterface $formula, FormulaToAnythingInterface $formulaToAnything): ?GeneratorInterface {

    $decorated = Generator::fromFormula($formula->getDecorated(), $formulaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    return new self(
      $decorated,
      $formula);
  }

  /**
   * @param \Donquixote\OCUI\Generator\GeneratorInterface $decorated
   * @param \Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface $formula
   */
  public function __construct(GeneratorInterface $decorated, Formula_OptionalInterface $formula) {
    $this->decorated = $decorated;
    $this->formula = $formula;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if (!\is_array($conf) || empty($conf['enabled'])) {
      return $this->formula->getEmptyPhp();
    }

    $subConf = $conf['options'] ?? null;

    return $this->decorated->confGetPhp($subConf);
  }
}
