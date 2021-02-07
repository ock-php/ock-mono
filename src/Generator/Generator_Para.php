<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Formula\Para\Formula_ParaInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\PhpUtil;

class Generator_Para implements GeneratorInterface {

  /**
   * @var \Donquixote\OCUI\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Generator\GeneratorInterface
   */
  private $paraGenerator;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Para\Formula_ParaInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function create(Formula_ParaInterface $formula, FormulaToAnythingInterface $formulaToAnything): Generator_Para {
    return new self(
      Generator::fromFormula($formula->getDecorated(), $formulaToAnything),
      Generator::fromFormula($formula->getParaFormula(), $formulaToAnything));
  }

  /**
   * @param \Donquixote\OCUI\Generator\GeneratorInterface $decorated
   * @param \Donquixote\OCUI\Generator\GeneratorInterface $paraGenerator
   */
  public function __construct(GeneratorInterface $decorated, GeneratorInterface $paraGenerator) {
    $this->decorated = $decorated;
    $this->paraGenerator = $paraGenerator;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    $paraConfPhp = $this->decorated->confGetPhp($conf);

    try {
      // @todo Use a service that can pass in variables!
      $paraConf = eval($paraConfPhp);
    }
    catch (\Exception $e) {
      return PhpUtil::incompatibleConfiguration($e->getMessage());
    }

    return $this->paraGenerator->confGetPhp($paraConf);
  }
}
