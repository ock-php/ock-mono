<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Drupal\ock\Formator\Optionable\OptionableFormatorD8Interface;
use Donquixote\Ock\Util\MessageUtil;
use Donquixote\Ock\Incarnator\Incarnator;
use Donquixote\Ock\Util\UtilBase;

final class FormatorD8 extends UtilBase {

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Drupal\ock\Formator\FormatorD8Interface
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException Transition seems to be unsupported for the given formula.
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
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Drupal\ock\Formator\FormatorD8Interface|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
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
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Drupal\ock\Formator\Optionable\OptionableFormatorD8Interface|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function optionable(
    FormulaInterface $formula,
    IncarnatorInterface $incarnator
  ): ?OptionableFormatorD8Interface {

    /** @var \Drupal\ock\Formator\Optionable\OptionableFormatorD8Interface|null $candidate */
    $candidate = Incarnator::incarnate(
      $formula,
      OptionableFormatorD8Interface::class,
      $incarnator);

    return $candidate;
  }

}
