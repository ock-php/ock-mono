<?php

namespace Drupal\renderkit8\EntityDisplay;

use Donquixote\Cf\Evaluator\Evaluator;
use Donquixote\Cf\Schema\CfSchema;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Summarizer\Summarizer;
use Drupal\renderkit8\Context\EntityContext;
use Drupal\renderkit8\Util\UtilBase;

final class EntityDisplay extends UtilBase {

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface|null
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public static function fromConf($conf, SchemaToAnythingInterface $schemaToAnything) {
    $schema = self::schema();
    $evaluator = Evaluator::fromSchema($schema, $schemaToAnything);
    $candidate = $evaluator->confGetValue($conf);

    if (!$candidate instanceof EntityDisplayInterface) {
      return NULL;
    }

    return $candidate;
  }

  /**
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function schema($entityType = NULL, $bundle = NULL) {

    if (NULL === $entityType) {
      return CfSchema::iface(EntityDisplayInterface::class);
    }

    return CfSchema::iface(
      EntityDisplayInterface::class,
      EntityContext::get($entityType, $bundle));
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return string|null
   */
  public static function summary($conf, SchemaToAnythingInterface $schemaToAnything) {

    $schema = self::schema();

    $summarizer = Summarizer::fromSchema(
      $schema,
      $schemaToAnything);

    return $summarizer->confGetSummary($conf);
  }
}
