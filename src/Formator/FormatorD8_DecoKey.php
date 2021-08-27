<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Donquixote\ObCK\Formula\DecoKey\Formula_DecoKeyInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;

class FormatorD8_DecoKey implements FormatorD8Interface {

  /**
   * @var \Drupal\cu\Formator\FormatorD8Interface
   */
  private FormatorD8Interface $decorated;

  /**
   * @var \Drupal\cu\Formator\FormatorD8Interface
   */
  private FormatorD8Interface $decorator;

  private string $key;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\DecoKey\Formula_DecoKeyInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function create(Formula_DecoKeyInterface $formula, FormulaToAnythingInterface $formulaToAnything): ?self {
    return new self(
      FormatorD8::fromFormula(
        $formula->getDecorated(),
        $formulaToAnything),
      FormatorD8::fromFormula(
        $formula->getDecoratorFormula(),
        $formulaToAnything),
      $formula->getDecoKey());
  }

  /**
   * Constructor.
   *
   * @param \Drupal\cu\Formator\FormatorD8Interface $decorated
   * @param \Drupal\cu\Formator\FormatorD8Interface $decorator
   * @param string $key
   */
  public function __construct(
    FormatorD8Interface $decorated,
    FormatorD8Interface $decorator,
    string $key
  ) {
    $this->decorated = $decorated;
    $this->decorator = $decorator;
    $this->key = $key;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {
    $main_form = $this->decorated->confGetD8Form($conf, $label);
    // @todo Support decorators.
    return $main_form;
  }
}
