<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Generator;

use Donquixote\ObCK\Formula\FixedConf\Formula_FixedConfInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;

class Generator_FixedConf implements GeneratorInterface {

  /**
   * @var \Donquixote\ObCK\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $conf;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\FixedConf\Formula_FixedConfInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\Generator\GeneratorInterface
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function create(Formula_FixedConfInterface $formula, FormulaToAnythingInterface $formulaToAnything): GeneratorInterface {
    return new self(
      Generator::fromFormula($formula->getDecorated(), $formulaToAnything),
      $formula->getConf());
  }

  /**
   * @param \Donquixote\ObCK\Generator\GeneratorInterface $decorated
   * @param mixed $conf
   */
  public function __construct(GeneratorInterface $decorated, $conf) {
    $this->decorated = $decorated;
    $this->conf = $conf;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {
    return $this->decorated->confGetPhp($this->conf);
  }
}
