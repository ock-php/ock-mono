<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Incarnator\IncarnatorInterface;
use Drupal\cu\Formator\Optionable\OptionableFormatorD8Interface;
use Donquixote\ObCK\Util\MessageUtil;
use Donquixote\ObCK\Incarnator\Incarnator;
use Donquixote\ObCK\Util\UtilBase;

final class FormatorD8 extends UtilBase {

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Drupal\cu\Formator\FormatorD8Interface
   *
   * @throws \Donquixote\ObCK\Exception\IncarnatorException Transition seems to be unsupported for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    IncarnatorInterface $incarnator
  ): FormatorD8Interface {

    $candidate = Incarnator::claim(
      $formula,
      FormatorD8Interface::class,
      $incarnator);

    if ($candidate instanceof FormatorD8Interface) {
      return $candidate;
    }

    throw new \RuntimeException(strtr(
      'Misbehaving FTA for formula of class @formula_class: Expected @interface object, found @found.',
      [
        '@formula_class' => get_class($formula),
        '@interface' => FormatorD8Interface::class,
        '@found' => MessageUtil::formatValue($candidate),
      ]));
  }

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Drupal\cu\Formator\FormatorD8Interface|null
   *
   * @throws \Donquixote\ObCK\Exception\IncarnatorException
   */
  public static function optional(
    FormulaInterface $formula,
    IncarnatorInterface $incarnator
  ): ?FormatorD8Interface {

    $optionable = self::optionable(
      $formula,
      $incarnator
    );

    if (NULL === $optionable) {
      return NULL;
    }

    return $optionable->getOptionalFormator();
  }

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Drupal\cu\Formator\Optionable\OptionableFormatorD8Interface|null
   *
   * @throws \Donquixote\ObCK\Exception\IncarnatorException
   */
  public static function optionable(
    FormulaInterface $formula,
    IncarnatorInterface $incarnator
  ): ?OptionableFormatorD8Interface {

    /** @var \Drupal\cu\Formator\Optionable\OptionableFormatorD8Interface|null $candidate */
    $candidate = Incarnator::incarnate(
      $formula,
      OptionableFormatorD8Interface::class,
      $incarnator);

    return $candidate;
  }

}
