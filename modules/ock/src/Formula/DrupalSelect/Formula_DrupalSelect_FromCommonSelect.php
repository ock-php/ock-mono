<?php

declare(strict_types=1);

namespace Drupal\ock\Formula\DrupalSelect;

use Drupal\Component\Render\MarkupInterface;
use Ock\Adaptism\Attribute\Adapter;
use Ock\Adaptism\Attribute\Parameter\Adaptee;
use Ock\Ock\Formula\Select\Formula_SelectInterface;
use Ock\Ock\Translator\TranslatorInterface;

/**
 * Adapter for OCK select formulas.
 *
 * @see \Drupal\ock\Formula\Formula_Select_FromDrupalSelect
 */
#[Adapter]
class Formula_DrupalSelect_FromCommonSelect implements Formula_DrupalSelectInterface {

  /**
   * Constructor.
   *
   * @param \Ock\Ock\Formula\Select\Formula_SelectInterface $decorated
   * @param \Ock\Ock\Translator\TranslatorInterface $translator
   */
  public function __construct(
    #[Adaptee]
    private readonly Formula_SelectInterface $decorated,
    private readonly TranslatorInterface $translator,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getGroupedOptions(): array {
    $optionsByGroupId = [];
    foreach ($this->decorated->getOptionsMap() as $id => $groupId) {
      $optionsByGroupId[$groupId][$id] = $this->decorated->idGetLabel($id)->convert($this->translator);
    }
    $optionss = [];
    foreach ($optionsByGroupId as $groupId => $optionsInGroup) {
      if ($groupId === '') {
        $groupLabelStr = '';
      }
      else {
        $groupLabelStr = $this->decorated->groupIdGetLabel($groupId)
          ?->convert($this->translator)
          ?? $groupId;
      }
      if (isset($options[$groupLabelStr])) {
        $optionss[$groupLabelStr] += $optionsInGroup;
      }
      else {
        $optionss[$groupLabelStr] = $optionsInGroup;
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
