<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Select;

use Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface;
use Donquixote\Cf\Text\TextInterface;

abstract class CfSchema_Select_TwoStepFlatSelectGrandBase implements CfSchema_SelectInterface {

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
        $options[$label0][$combinedId] = $this->combineLabels($label0, $label1);
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
   * @param string $label0
   * @param string $label1
   *
   * @return string
   */
  protected function combineLabels($label0, $label1): string {
    return $label0 . ' - ' . $label1;
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
  protected function splitId($combinedId): array {
    return explode(':', $combinedId);
  }

  /**
   * @return \Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface
   */
  abstract protected function getIdSchema(): CfSchema_FlatSelectInterface;

  /**
* @param string $id
   *
   * @return \Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface|null
   */
  abstract protected function idGetSubSchema(string $id): ?CfSchema_FlatSelectInterface;
}
