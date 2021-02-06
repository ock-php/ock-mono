<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select;

use Donquixote\OCUI\IdToSchema\IdToSchemaInterface;
use Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\OCUI\Formula\DrilldownVal\CfSchema_DrilldownValInterface;
use Donquixote\OCUI\Formula\Id\Formula_IdInterface;
use Donquixote\OCUI\Text\TextInterface;

class CfSchema_Select_InlineExpanded implements CfSchema_SelectInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Select\CfSchema_SelectInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\IdToSchema\IdToSchemaInterface
   */
  private $idToSchema;

  /**
   * @param \Donquixote\OCUI\Formula\Select\CfSchema_SelectInterface $decorated
   * @param \Donquixote\OCUI\IdToSchema\IdToSchemaInterface $idToSchema
   */
  public function __construct(
    CfSchema_SelectInterface $decorated,
    IdToSchemaInterface $idToSchema
  ) {
    $this->decorated = $decorated;
    $this->idToSchema = $idToSchema;
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupedOptions(): array {

    $options = [];
    /** @var string[] $groupOptions */
    foreach ($this->decorated->getGroupedOptions() as $groupLabel => $groupOptions) {
      foreach ($groupOptions as $id => $label) {

        if (NULL === $inlineOptions = $this->idGetInlineOptions($id)) {
          $options[$groupLabel][$id] = $label;
        }
        else {
          foreach ($inlineOptions as $inlineGroupLabel => $inlineGroupOptions) {
            foreach ($inlineGroupOptions as $inlineId => $inlineLabel) {
              $options[$inlineGroupLabel]["$id/$inlineId"] = "$label: $inlineLabel";
            }
          }
          $options[$groupLabel][$id] = "$label - ALL";
        }
      }
    }

    return $options;
  }

  /**
   * @param string $id
   *
   * @return null|string[][]
   */
  private function idGetInlineOptions(string $id): ?array {

    if (NULL === $schema = $this->idGetSelectSchema($id)) {
      return NULL;
    }

    return $schema->getGroupedOptions();
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {

    if (FALSE === /* $pos = */ strpos($id, '/')) {
      return $this->decorated->idGetLabel($id);
    }

    [$prefix, $suffix] = explode('/', $id, 2);

    if (NULL === $subSchema = $this->idGetSelectSchema($prefix)) {
      return NULL;
    }

    return $subSchema->idGetLabel($suffix);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($combinedId): bool {

    if (FALSE === /* $pos = */ strpos($combinedId, '/')) {
      return $this->decorated->idIsKnown($combinedId);
    }

    [$prefix, $suffix] = explode('/', $combinedId, 2);

    if (NULL === $subSchema = $this->idGetSelectSchema($prefix)) {
      return FALSE;
    }

    return $subSchema->idIsKnown($suffix);
  }

  /**
   * @param string|int $id
   *
   * @return \Donquixote\OCUI\Formula\Select\CfSchema_SelectInterface|null
   */
  private function idGetSelectSchema($id): ?CfSchema_SelectInterface {

    if (NULL === $idSchema = $this->idGetIdSchema($id)) {
      return NULL;
    }

    if (!$idSchema instanceof CfSchema_SelectInterface) {
      return NULL;
    }

    return $idSchema;
  }

  /**
* @param string $id
   *
   * @return \Donquixote\OCUI\Formula\Id\Formula_IdInterface|null
   */
  private function idGetIdSchema(string $id): ?Formula_IdInterface {

    if (NULL === $nestedSchema = $this->idToSchema->idGetSchema($id)) {
      return NULL;
    }

    if ($nestedSchema instanceof Formula_DrilldownInterface) {
      return $nestedSchema->getIdSchema();
    }

    if ($nestedSchema instanceof Formula_IdInterface) {
      return $nestedSchema;
    }

    if ($nestedSchema instanceof CfSchema_DrilldownValInterface) {
      return $nestedSchema->getDecorated()->getIdSchema();
    }

    return NULL;

  }
}
