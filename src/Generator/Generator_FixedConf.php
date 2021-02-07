<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Formula\FixedConf\Formula_FixedConfInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

class Generator_FixedConf implements GeneratorInterface {

  /**
   * @var \Donquixote\OCUI\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $conf;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\FixedConf\Formula_FixedConfInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\OCUI\Generator\GeneratorInterface
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function create(Formula_FixedConfInterface $formula, FormulaToAnythingInterface $formulaToAnything): GeneratorInterface {
    return new self(
      Generator::fromFormula($formula->getDecorated(), $formulaToAnything),
      $formula->getConf());
  }

  /**
   * @param \Donquixote\OCUI\Generator\GeneratorInterface $decorated
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
