<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select;

use Donquixote\OCUI\Formula\Select\Flat\Formula_FlatSelectInterface;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;

abstract class Formula_Select_TwoStepFlatSelectGrandBase implements Formula_SelectInterface {

  /**
   * {@inheritdoc}
   */
  public function getGroupedOptions(): array {

    $options = [];
    foreach ($this->getIdSchema()->getOptions() as $id0 => $label0) {

      if (null === $subSchema = $this->idGetSubSchema($id0)) {
        continue;
      }

      foreach ($subSchema->getOptions() as $id1 => $label1) {
        $combinedId = $this->combineIds($id0, $id1);
        // @todo Find a way to use TextInterface for group labels.
        $options[$id0][$combinedId] = $this->combineLabels($label0, $label1);
      }
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($combinedId): bool {
    [$id0, $id1] = $this->splitId($combinedId) + [NULL, NULL];

    if (NULL === $id1) {
      return FALSE;
    }

    if (!$this->getIdSchema()->idIsKnown($id0)) {
      return FALSE;
    }

    if (null === $subSchema = $this->idGetSubSchema($id0)) {
      return FALSE;
    }

    return $subSchema->idIsKnown($id1);
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($combinedId): ?TextInterface {
    [$id0, $id1] = $this->splitId($combinedId) + [NULL, NULL];

    if (NULL === $id1) {
      return NULL;
    }

    if (NULL === $label0 = $this->getIdSchema()->idGetLabel($id0)) {
      return NULL;
    }

    if (NULL === $subSchema = $this->idGetSubSchema($id0)) {
      return NULL;
    }

    if (NULL === $label1 = $subSchema->idGetLabel($id1)) {
      return NULL;
    }

    return $this->combineLabels($label0, $label1);
  }

  /**
   * @param \Donquixote\OCUI\Text\TextInterface $label0
   * @param \Donquixote\OCUI\Text\TextInterface $label1
   *
   * @return \Donquixote\OCUI\Text\TextInterface
   */
  protected function combineLabels(TextInterface $label0, TextInterface $label1): TextInterface {
    return Text::concat([$label0, $label1], ' - ');
  }

  /**
   * @param string $id0
   * @param string $id1
   *
   * @return string
   */
  protected function combineIds(string $id0, string $id1): string {
    return $id0 . ':' . $id1;
  }

  /**
   * @param string $combinedId
   *
   * @return string[]
   *   Format: [$id0, $id1]
   */
  protected function splitId(string $combinedId): array {
    return explode(':', $combinedId);
  }

  /**
   * @return \Donquixote\OCUI\Formula\Select\Flat\Formula_FlatSelectInterface
   */
  abstract protected function getIdSchema(): Formula_FlatSelectInterface;

  /**
* @param string $id
   *
   * @return \Donquixote\OCUI\Formula\Select\Flat\Formula_FlatSelectInterface|null
   */
  abstract protected function idGetSubSchema(string $id): ?Formula_FlatSelectInterface;
}
