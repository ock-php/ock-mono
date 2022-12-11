<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\FormulaAdapter;
use Donquixote\Ock\Translator\Translator;
use Drupal\Component\Render\MarkupInterface;

class FormatorD8_Group implements FormatorD8Interface {

  /**
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   *
   * @return self
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function create(Formula_GroupInterface $formula, UniversalAdapterInterface $adapter): self {
    $itemFormators = FormulaAdapter::getMultiple(
      $formula->getItemFormulas(),
      FormatorD8Interface::class,
      $adapter,
    );
    return new self($itemFormators, $formula->getLabels());
  }

  /**
   * @param \Drupal\ock\Formator\FormatorD8Interface[] $itemFormators
   * @param \Donquixote\Ock\Text\TextInterface[] $labels
   */
  public function __construct(
    private readonly array $itemFormators,
    private readonly array $labels,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {

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
