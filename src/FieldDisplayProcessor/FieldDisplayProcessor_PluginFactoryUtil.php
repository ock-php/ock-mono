<?php
declare(strict_types=1);

namespace Drupal\renderkit\FieldDisplayProcessor;

use Donquixote\Ock\Formula\GroupVal\Formula_GroupVal;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\Ock\Formula\ValueProvider\Formula_ValueProvider_FixedPhp;
use Drupal\renderkit\Formula\Formula_FieldDisplayProcessor_Label;
use Drupal\renderkit\Formula\Formula_FieldDisplayProcessor_OuterContainer;
use Drupal\renderkit\Util\UtilBase;

abstract class FieldDisplayProcessor_PluginFactoryUtil extends UtilBase implements FieldDisplayProcessorInterface {

  /**
   * @CfrPlugin("minimalDefault", @t("Minimal, default"))
   *
   * @return \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  public static function minimalDefault(): FieldDisplayProcessor_OuterContainer|FieldDisplayProcessorInterface {
    $fdp = self::fullResetDefault();
    $fdp = new FieldDisplayProcessor_OuterContainer($fdp);
    return $fdp;
  }

  /**
   * @CfrPlugin("fullResetDefault", @t("Full reset, default"))
   *
   * @return \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  public static function fullResetDefault(): FieldDisplayProcessor_Label|FieldDisplayProcessorInterface {
    $fdp = new FieldDisplayProcessor_Bare();
    $fdp = new FieldDisplayProcessor_Label($fdp);
    return $fdp;
  }

  /**
   * @CfrPlugin("minimal", @t("Minimal"))
   *
   * @return \Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface
   */
  public static function minimal(): Formula_GroupValInterface {
    return Formula_FieldDisplayProcessor_OuterContainer::create(
      self::fullReset(),
      FALSE)
      ->getValFormula();
  }

  /**
   * @CfrPlugin("fullReset", @t("Full reset"))
   *
   * @return \Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface
   */
  public static function fullReset(): Formula_GroupValInterface {
    return Formula_FieldDisplayProcessor_Label::create(
      self::bare())
      ->getValFormula();
  }

  /**
   * @return \Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface
   */
  private static function bare(): Formula_GroupValInterface {
    return Formula_GroupVal::createEmpty(
      Formula_ValueProvider_FixedPhp::fromClass(
        FieldDisplayProcessor_Bare::class));
  }

}
