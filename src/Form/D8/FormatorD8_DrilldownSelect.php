<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D8;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\DrilldownKeysHelper\DrilldownKeysHelper;
use Donquixote\Cf\DrilldownKeysHelper\DrilldownKeysHelperInterface;
use Donquixote\Cf\Exception\SchemaToAnythingException;
use Donquixote\Cf\Form\D8\Optionable\OptionableFormatorD8Interface;
use Donquixote\Cf\Form\D8\Util\D8FormUtil;
use Donquixote\Cf\Form\D8\Util\D8SelectUtil;
use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Donquixote\Cf\Optionlessness\OptionlessnessInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\Select\CfSchema_SelectInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\ConfUtil;
use Drupal\Core\Form\FormStateInterface;

class FormatorD8_DrilldownSelect extends FormatorD8_DrilldownSelectBase {

  /**
   * @var \Donquixote\Cf\IdToSchema\IdToSchemaInterface
   */
  private $idToSchema;

  /**
   * @var \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
   */
  private $schemaToAnything;

  /**
   * @var \Donquixote\Cf\Form\D8\FormatorD8Interface[]|false[]
   */
  private $formators = [];

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $drilldown
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  public static function create(CfSchema_DrilldownInterface $drilldown, SchemaToAnythingInterface $schemaToAnything) {

    $idSchema = $drilldown->getIdSchema();

    if (!$idSchema instanceof CfSchema_SelectInterface) {
      // Not supported. Write your own formator.
      return NULL;
    }

    $idToSchema = $drilldown->getIdToSchema();

    return new self(
      $idSchema,
      $idToSchema,
      DrilldownKeysHelper::fromSchema($drilldown),
      $schemaToAnything);
  }

  /**
   * @param \Donquixote\Cf\Schema\Select\CfSchema_SelectInterface $idSchema
   * @param \Donquixote\Cf\IdToSchema\IdToSchemaInterface $idToSchema
   * @param \Donquixote\Cf\DrilldownKeysHelper\DrilldownKeysHelperInterface $drilldownKeysHelper
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   */
  public function __construct(
    CfSchema_SelectInterface $idSchema,
    IdToSchemaInterface $idToSchema,
    DrilldownKeysHelperInterface $drilldownKeysHelper,
    SchemaToAnythingInterface $schemaToAnything
  ) {
    $this->idToSchema = $idToSchema;
    $this->schemaToAnything = $schemaToAnything;
    parent::__construct($idSchema, $drilldownKeysHelper);
  }

  /**
   * @inheritDoc
   */
  protected function idIsOptionless(string $id): bool {
    $idSchema = $this->idToSchema->idGetSchema($id);
    if (NULL === $idSchema) {
      return FALSE;
    }
    return $this->schemaIsOptionless($idSchema);
  }

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   *
   * @return bool
   */
  private function schemaIsOptionless(CfSchemaInterface $schema): bool {

    $optionlessnessOrNull = $this->schemaToAnything->schema(
      $schema,
      OptionlessnessInterface::class);

    return 1
      && NULL !== $optionlessnessOrNull
      && $optionlessnessOrNull instanceof OptionlessnessInterface
      && $optionlessnessOrNull->isOptionless();
  }

  /**
   * @inheritDoc
   */
  protected function idGetSubform(string $id, $subConf): array {

    try {
      if (false === $subFormator = $this->idGetFormatorOrFalse($id)) {
        return [];
      }
    }
    catch (SchemaToAnythingException $e) {
      $subFormator = new FormatorD8_Broken($e->getMessage());
    }

    return $subFormator->confGetD8Form($subConf, NULL);
  }

  /**
   * @param string $id
   *
   * @return \Donquixote\Cf\Form\D8\FormatorD8Interface|false
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  private function idGetFormatorOrFalse($id) {
    return $this->formators[$id]
      ?? $this->formators[$id] = $this->idBuildFormatorOrFalse($id);
  }

  /**
   * @param string $id
   *
   * @return \Donquixote\Cf\Form\D8\FormatorD8Interface|false
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  private function idBuildFormatorOrFalse($id) {

    if (NULL === $schema = $this->idToSchema->idGetSchema($id)) {
      return FALSE;
    }

    if (NULL === $formator = FormatorD8::fromSchema(
        $schema,
        $this->schemaToAnything
      )
    ) {
      return FALSE;
    }

    return $formator;
  }

}
