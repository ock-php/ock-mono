<?php

declare(strict_types=1);

namespace Drupal\ock\Formula\DrupalSelect;

use Ock\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Ock\Ock\Translator\TranslatorInterface;
use Drupal\Component\Render\MarkupInterface;

/**
 * Alternative select formula with Drupal label types.
 */
class Formula_DrupalSelect_FromFlatSelect implements Formula_DrupalSelectInterface {

  /**
   * Constructor.
   *
   * @param \Ock\Ock\Formula\Select\Flat\Formula_FlatSelectInterface $decorated
   * @param \Ock\Ock\Translator\TranslatorInterface $translator
   */
  public function __construct(private readonly Formula_FlatSelectInterface $decorated, private TranslatorInterface $translator) {
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupedOptions(): array {
    $options = [];
    foreach ($this->decorated->getOptions() as $id => $label) {
      $options[$id] = $label->convert($this->translator);
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(string|int $id): string|MarkupInterface|null {
    if (NULL === $label = $this->decorated->idGetLabel($id)) {
      return NULL;
    }
    return $label->convert($this->translator);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(string|int $id): bool {
    return $this->decorated->idIsKnown($id);
  }

}
