<?php

declare(strict_types=1);

namespace Drupal\cu\Formula\DrupalSelect;

use Donquixote\ObCK\Formula\Select\Flat\Formula_FlatSelectInterface;
use Donquixote\ObCK\Translator\TranslatorInterface;

/**
 * Alternative select formula with Drupal label types.
 */
class Formula_DrupalSelect_FromFlatSelect implements Formula_DrupalSelectInterface {

  /**
   * @var \Donquixote\ObCK\Formula\Select\Flat\Formula_FlatSelectInterface
   */
  private Formula_FlatSelectInterface $decorated;

  /**
   * @var \Donquixote\ObCK\Translator\TranslatorInterface
   */
  private $translator;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Formula\Select\Flat\Formula_FlatSelectInterface $decorated
   * @param \Donquixote\ObCK\Translator\TranslatorInterface $translator
   */
  public function __construct(Formula_FlatSelectInterface $decorated, TranslatorInterface $translator) {
    $this->decorated = $decorated;
    $this->translator = $translator;
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
  public function idGetLabel($id) {
    if (NULL === $label = $this->decorated->idGetLabel($id)) {
      return NULL;
    }
    return $label->convert($this->translator);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {
    return $this->decorated->idIsKnown($id);
  }

}
