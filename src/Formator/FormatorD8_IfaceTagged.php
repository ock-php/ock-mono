<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Drupal\ock\Formator\Optionable\OptionableFormatorD8Interface;
use Donquixote\Ock\Formula\Neutral\Formula_Neutral_IfaceTransformed;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

class FormatorD8_IfaceTagged implements FormatorD8Interface, OptionableFormatorD8Interface {

  /**
   * @var \Drupal\ock\Formator\FormatorD8_DrilldownSelect
   */
  private $decorated;

  /**
   * @var \Donquixote\Ock\Formula\Neutral\Formula_Neutral_IfaceTransformed
   */
  private $formula;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Neutral\Formula_Neutral_IfaceTransformed $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(
    Formula_Neutral_IfaceTransformed $formula,
    IncarnatorInterface $incarnator
  ): ?FormatorD8_IfaceTagged {
    $decorated = FormatorD8::fromFormula(
      $formula->getDecorated(),
      $incarnator
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
   * @param \Drupal\ock\Formator\FormatorD8_DrilldownSelect $decorated
   * @param \Donquixote\Ock\Formula\Neutral\Formula_Neutral_IfaceTransformed $formula
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

    /* @see \Drupal\ock\Element\RenderElement_DrilldownContainer */
    $form['#type'] = 'ock_drilldown_container';
    $form['#ock_interface'] = $this->formula->getInterface();
    $form['#ock_context'] = $this->formula->getContext();

    $form['#attached']['library'][] = 'ock/drilldown-tools';

    return $form;
  }
}
