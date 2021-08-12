<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Donquixote\OCUI\Formula\Label\Formula_LabelInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

class FormatorD8_Label implements FormatorD8Interface {

  /**
   * @var \Drupal\cu\Formator\FormatorD8Interface
   */
  private $decorated;

  /**
   * @var string
   */
  private $label;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Label\Formula_LabelInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function create(Formula_LabelInterface $formula, FormulaToAnythingInterface $formulaToAnything) {

    if (NULL === $decorated = FormatorD8::fromFormula(
        $formula->getDecorated(),
        $formulaToAnything
      )
    ) {
      return NULL;
    }

    return new self($decorated, $formula->getLabel());
  }

  /**
   * @param \Drupal\cu\Formator\FormatorD8Interface $decorated
   * @param string $label
   */
  public function __construct(FormatorD8Interface $decorated, $label) {
    $this->decorated = $decorated;
    $this->label = $label;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

    return $this->decorated->confGetD8Form($conf, $this->label);
  }
}
