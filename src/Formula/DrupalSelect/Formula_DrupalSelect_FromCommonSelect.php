<?php

declare(strict_types=1);

namespace Drupal\cu\Formula\DrupalSelect;

use Donquixote\ObCK\Formula\Select\Formula_SelectInterface;
use Donquixote\ObCK\Translator\TranslatorInterface;

/**
 * Adapter for OCK select formulas.
 *
 * @see \Drupal\cu\Formula\Formula_Select_FromDrupalSelect
 */
class Formula_DrupalSelect_FromCommonSelect implements Formula_DrupalSelectInterface {

  /**
   * @var \Donquixote\ObCK\Formula\Select\Formula_SelectInterface
   */
  private Formula_SelectInterface $decorated;

  /**
   * @var \Donquixote\ObCK\Translator\TranslatorInterface
   */
  private TranslatorInterface $translator;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Formula\Select\Formula_SelectInterface $decorated
   * @param \Donquixote\ObCK\Translator\TranslatorInterface $translator
   */
  public function __construct(Formula_SelectInterface $decorated, TranslatorInterface $translator) {
    $this->decorated = $decorated;
    $this->translator = $translator;
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupedOptions(): array {
    $groups = $this->decorated->getOptGroups();
    /** @var \Donquixote\ObCK\Text\TextInterface[][] $labelss */
    $labelss[''] = $this->decorated->getOptions(NULL);
    foreach ($groups as $group_id => $group_label) {
      $group_label_str = $group_label->convert($this->translator);
      foreach ($this->decorated->getOptions($group_id) as $id => $label) {
        $labelss[$group_label_str][$id] = $label;
      }
    }
    $optionss = [];
    foreach ($labelss as $group_label => $labels_in_group) {
      foreach ($labels_in_group as $id => $label) {
        $optionss[$group_label][$id] = $label->convert($this->translator);
      }
    }
    return $optionss;
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
