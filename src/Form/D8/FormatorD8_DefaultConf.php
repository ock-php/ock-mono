<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Form\D8;

use Donquixote\OCUI\Formula\DefaultConf\Formula_DefaultConfInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

class FormatorD8_DefaultConf implements FormatorD8Interface {

  /**
   * @var \Donquixote\OCUI\Form\D8\FormatorD8Interface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $defaultConf;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\DefaultConf\Formula_DefaultConfInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function create(
    Formula_DefaultConfInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?FormatorD8_DefaultConf {

    $decorated = FormatorD8::fromFormula(
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
   * @param \Donquixote\OCUI\Form\D8\FormatorD8Interface $decorated
   * @param mixed $defaultConf
   */
  public function __construct(FormatorD8Interface $decorated, $defaultConf) {
    $this->decorated = $decorated;
    $this->defaultConf = $defaultConf;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

    if (NULL === $conf) {
      $conf = $this->defaultConf;
    }

    return $this->decorated->confGetD8Form($conf, $label);
  }

}
