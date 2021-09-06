<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Evaluator\Evaluator;
use Donquixote\Cf\Evaluator\EvaluatorInterface;
use Donquixote\Cf\Schema\CfSchema;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Summarizer\Summarizer;
use Drupal\cfrapi\Exception\UnsupportedSchemaException;
use Drupal\renderkit\Context\EntityContext;
use Drupal\renderkit\Util\UtilBase;

final class EntityDisplay extends UtilBase {

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplayInterface|null
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public static function fromConf($conf, SchemaToAnythingInterface $schemaToAnything): ?EntityDisplayInterface {

    $evaluator = self::evaluatorOrNull($schemaToAnything);

    if (null === $evaluator) {
      throw new UnsupportedSchemaException("Failed to create evaluator from schema.");
    }

    $candidate = $evaluator->confGetValue($conf);

    if (!$candidate instanceof EntityDisplayInterface) {
      return NULL;
    }

    return $candidate;
  }

  /**
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Evaluator\EvaluatorInterface|null
   */
  public static function evaluatorOrNull(SchemaToAnythingInterface $schemaToAnything): ?EvaluatorInterface {

    return Evaluator::fromSchema(
      self::schema(),
      $schemaToAnything);
  }

  /**
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function schema($entityType = NULL, $bundle = NULL): CfSchemaInterface {

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
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public static function summary($conf, SchemaToAnythingInterface $schemaToAnything): ?string {

    $schema = self::schema();

    $summarizer = Summarizer::fromSchema(
      $schema,
      $schemaToAnything);

    if (null === $summarizer) {
      throw new UnsupportedSchemaException("Failed to create summarizer from schema.");
    }

    return $summarizer->confGetSummary($conf);
  }
}
