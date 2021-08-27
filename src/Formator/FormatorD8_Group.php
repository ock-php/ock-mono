<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Donquixote\ObCK\Formula\Group\Formula_GroupInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Translator\Lookup\TranslatorLookup_Passthru;
use Donquixote\ObCK\Translator\Translator;
use Donquixote\ObCK\Util\StaUtil;
use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;

class FormatorD8_Group implements FormatorD8Interface {

  /**
   * @var \Drupal\cu\Formator\FormatorD8Interface[]
   */
  private $itemFormators;

  /**
   * @var \Donquixote\ObCK\Text\TextInterface[]
   */
  private $labels;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
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
   * @param \Drupal\cu\Formator\FormatorD8Interface[] $itemFormators
   * @param \Donquixote\ObCK\Text\TextInterface[] $labels
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

    $form['#attached']['library'][] = 'cu/form';

    return $form;
  }
}
