<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Form\D8;

use Donquixote\OCUI\Form\D8\Optionable\OptionableFormatorD8Interface;
use Donquixote\OCUI\Formula\Neutral\Formula_Neutral_IfaceTransformed;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

class FormatorD8_IfaceTagged implements FormatorD8Interface, OptionableFormatorD8Interface {

  /**
   * @var \Donquixote\OCUI\Form\D8\FormatorD8_DrilldownSelect
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Formula\Neutral\Formula_Neutral_IfaceTransformed
   */
  private $formula;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Neutral\Formula_Neutral_IfaceTransformed $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function create(
    Formula_Neutral_IfaceTransformed $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?FormatorD8_IfaceTagged {
    $decorated = FormatorD8::fromFormula(
      $formula->getDecorated(),
      $formulaToAnything
    );

    if (NULL === $decorated) {
      return NULL;
    }

    if (!$decorated instanceof FormatorD8_DrilldownSelect) {
      return NULL;
    }

    return new self($decorated, $formula);
  }

  /**
   * @param \Donquixote\OCUI\Form\D8\FormatorD8_DrilldownSelect $decorated
   * @param \Donquixote\OCUI\Formula\Neutral\Formula_Neutral_IfaceTransformed $formula
   */
  public function __construct(
    FormatorD8_DrilldownSelect $decorated,
    Formula_Neutral_IfaceTransformed $formula
  ) {
    $this->decorated = $decorated;
    $this->formula = $formula;
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionalFormator(): ?FormatorD8Interface {

    if (NULL === $decorated = $this->decorated->getOptionalFormator()) {
      return NULL;
    }

    if (!$decorated instanceof FormatorD8_DrilldownSelect) {
      return NULL;
    }

    return new self($decorated, $this->formula);
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

    $form = $this->decorated->confGetD8Form($conf, $label);

    /* @see \Drupal\faktoria\Element\RenderElement_DrilldownContainer */
    $form['#type'] = 'faktoria_drilldown_container';
    $form['#faktoria_interface'] = $this->formula->getInterface();
    $form['#faktoria_context'] = $this->formula->getContext();

    $form['#attached']['library'][] = 'faktoria/drilldown-tools';

    return $form;
  }
}
