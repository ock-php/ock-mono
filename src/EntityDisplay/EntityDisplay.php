<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Evaluator\Evaluator;
use Donquixote\Ock\Evaluator\EvaluatorInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Summarizer\Summarizer;
use Drupal\cfrapi\Exception\UnsupportedFormulaException;
use Drupal\renderkit\Context\EntityContext;
use Drupal\renderkit\Util\UtilBase;

final class EntityDisplay extends UtilBase {

  /**
   * @param mixed $conf
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplayInterface|null
   */
  public static function fromConf($conf, IncarnatorInterface $incarnator): ?EntityDisplayInterface {

    $evaluator = self::evaluatorOrNull($incarnator);

    if (null === $evaluator) {
      throw new UnsupportedFormulaException("Failed to create evaluator from formula.");
    }

    $candidate = $evaluator->confGetValue($conf);

    if (!$candidate instanceof EntityDisplayInterface) {
      return NULL;
    }

    return $candidate;
  }

  /**
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Donquixote\Ock\Evaluator\EvaluatorInterface|null
   */
  public static function evaluatorOrNull(IncarnatorInterface $incarnator): ?EvaluatorInterface {

    return Evaluator::fromFormula(
      self::formula(),
      $incarnator);
  }

  /**
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function formula($entityType = NULL, $bundle = NULL): FormulaInterface {

    if (NULL === $entityType) {
      return Formula::iface(EntityDisplayInterface::class);
    }

    return Formula::iface(
      EntityDisplayInterface::class,
      EntityContext::get($entityType, $bundle));
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return string|null
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedFormulaException
   */
  public static function summary($conf, IncarnatorInterface $incarnator): ?string {

    $formula = self::formula();

    $summarizer = Summarizer::fromFormula(
      $formula,
      $incarnator);

    if (null === $summarizer) {
      throw new UnsupportedFormulaException("Failed to create summarizer from formula.");
    }

    return $summarizer->confGetSummary($conf);
  }
}
