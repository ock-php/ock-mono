<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Form\D8;

use Donquixote\OCUI\Formula\Group\Formula_GroupInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\StaUtil;

class FormatorD8_Group implements FormatorD8Interface {

  /**
   * @var \Donquixote\OCUI\Form\D8\FormatorD8Interface[]
   */
  private $itemFormators;

  /**
   * @var string[]
   */
  private $labels;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\OCUI\Form\D8\FormatorD8_Group|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function create(Formula_GroupInterface $formula, FormulaToAnythingInterface $formulaToAnything) {

    if (NULL === $itemFormators = StaUtil::getMultiple(
      $formula->getItemFormulas(),
      $formulaToAnything,
      FormatorD8Interface::class)
    ) {
      return NULL;
    }

    return new self($itemFormators, $formula->getLabels());
  }

  /**
   * @param \Donquixote\OCUI\Form\D8\FormatorD8Interface[] $itemFormators
   * @param string[] $labels
   */
  public function __construct(array $itemFormators, array $labels) {
    $this->itemFormators = $itemFormators;
    $this->labels = $labels;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

    if (!\is_array($conf)) {
      $conf = [];
    }

    $form = [];

    if (NULL !== $label && '' !== $label) {
      $form['#title'] = $label;
    }

    foreach ($this->itemFormators as $key => $itemFormator) {

      $itemConf = $conf[$key] ?? null;

      $itemLabel = $this->labels[$key] ?? $key;

      $form[$key] = $itemFormator->confGetD8Form($itemConf, $itemLabel);
    }

    $form['#attached']['library'][] = 'faktoria/form';

    return $form;
  }
}
