<?php

declare(strict_types=1);

namespace Drupal\ock\Formula\DrupalSelect;

use Donquixote\Ock\Formula\Select\Formula_SelectInterface;
use Donquixote\Ock\Translator\TranslatorInterface;
use Drupal\Component\Render\MarkupInterface;

/**
 * Adapter for OCK select formulas.
 *
 * @see \Drupal\ock\Formula\Formula_Select_FromDrupalSelect
 */
class Formula_DrupalSelect_FromCommonSelect implements Formula_DrupalSelectInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\Select\Formula_SelectInterface $decorated
   * @param \Donquixote\Ock\Translator\TranslatorInterface $translator
   */
  public function __construct(private readonly Formula_SelectInterface $decorated, private TranslatorInterface $translator) {
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupedOptions(): array {
    $groups = $this->decorated->getOptGroups();
    /** @var \Donquixote\Ock\Text\TextInterface[][] $labelss */
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
