<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\Incarnator\Incarnator;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Translator\Translator;

class FormatorD8_Group implements FormatorD8Interface {

  /**
   * @var \Drupal\ock\Formator\FormatorD8Interface[]
   */
  private $itemFormators;

  /**
   * @var \Donquixote\Ock\Text\TextInterface[]
   */
  private $labels;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(Formula_GroupInterface $formula, IncarnatorInterface $incarnator): ?self {

    if (NULL === $itemFormators = Incarnator::multiple(
        $formula->getItemFormulas(),
        FormatorD8Interface::class,
        $incarnator)
    ) {
      return NULL;
    }

    return new self($itemFormators, $formula->getLabels());
  }

  /**
   * @param \Drupal\ock\Formator\FormatorD8Interface[] $itemFormators
   * @param \Donquixote\Ock\Text\TextInterface[] $labels
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

    $translator = Translator::passthru();

    foreach ($this->itemFormators as $key => $itemFormator) {

      $itemConf = $conf[$key] ?? null;

      $itemLabel = isset($this->labels[$key])
        ? $this->labels[$key]->convert($translator)
        : NULL;

      $form[$key] = $itemFormator->confGetD8Form($itemConf, $itemLabel);
    }

    $form['#attached']['library'][] = 'ock/form';

    return $form;
  }
}
