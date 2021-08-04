<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Form\D8;

use Donquixote\OCUI\Formula\Group\Formula_GroupInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Translator\Lookup\TranslatorLookup_Passthru;
use Donquixote\OCUI\Translator\Translator;
use Donquixote\OCUI\Util\StaUtil;
use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;

class FormatorD8_Group implements FormatorD8Interface {

  /**
   * @var \Donquixote\OCUI\Form\D8\FormatorD8Interface[]
   */
  private $itemFormators;

  /**
   * @var \Donquixote\OCUI\Text\TextInterface[]
   */
  private $labels;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function create(Formula_GroupInterface $formula, FormulaToAnythingInterface $formulaToAnything): ?self {

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
   * @param \Donquixote\OCUI\Text\TextInterface[] $labels
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

    $translator = new Translator(new TranslatorLookup_Passthru());

    foreach ($this->itemFormators as $key => $itemFormator) {

      $itemConf = $conf[$key] ?? null;

      $itemLabel = isset($this->labels[$key])
        ? $this->labels[$key]->convert($translator)
        : NULL;

      $form[$key] = $itemFormator->confGetD8Form($itemConf, $itemLabel);
    }

    $form['#attached']['library'][] = 'faktoria/form';

    return $form;
  }
}
