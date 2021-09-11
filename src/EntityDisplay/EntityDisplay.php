<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Evaluator\Evaluator;
use Donquixote\Ock\Evaluator\EvaluatorInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\Ock\Summarizer\Summarizer;
use Drupal\cfrapi\Exception\UnsupportedFormulaException;
use Drupal\renderkit\Context\EntityContext;
use Drupal\renderkit\Util\UtilBase;

final class EntityDisplay extends UtilBase {

  /**
   * @param mixed $conf
   * @param \Donquixote\Ock\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplayInterface|null
   *
   * @throws \Donquixote\Ock\Exception\EvaluatorException
   * @throws \Drupal\cfrapi\Exception\UnsupportedFormulaException
   */
  public static function fromConf($conf, FormulaToAnythingInterface $formulaToAnything): ?EntityDisplayInterface {

    $evaluator = self::evaluatorOrNull($formulaToAnything);

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
   * @param \Donquixote\Ock\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\Ock\Evaluator\EvaluatorInterface|null
   */
  public static function evaluatorOrNull(FormulaToAnythingInterface $formulaToAnything): ?EvaluatorInterface {

    return Evaluator::fromFormula(
      self::formula(),
      $formulaToAnything);
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
   * @param \Donquixote\Ock\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return string|null
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedFormulaException
   */
  public static function summary($conf, FormulaToAnythingInterface $formulaToAnything): ?string {

    $formula = self::formula();

    $summarizer = Summarizer::fromFormula(
      $formula,
      $formulaToAnything);

    if (null === $summarizer) {
      throw new UnsupportedFormulaException("Failed to create summarizer from formula.");
    }

    return $summarizer->confGetSummary($conf);
  }
}
