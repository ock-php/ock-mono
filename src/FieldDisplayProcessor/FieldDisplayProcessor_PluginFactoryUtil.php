<?php
declare(strict_types=1);

namespace Drupal\renderkit\FieldDisplayProcessor;

use Donquixote\ObCK\Formula\GroupVal\Formula_GroupVal;
use Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProvider_FixedValue;
use Drupal\renderkit\Formula\Formula_FieldDisplayProcessor_Label;
use Drupal\renderkit\Formula\Formula_FieldDisplayProcessor_OuterContainer;
use Drupal\renderkit\Util\UtilBase;

abstract class FieldDisplayProcessor_PluginFactoryUtil extends UtilBase implements FieldDisplayProcessorInterface {

  /**
   * @CfrPlugin("minimalDefault", @t("Minimal, default"))
   *
   * @return \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  public static function minimalDefault() {
    $fdp = self::fullResetDefault();
    $fdp = new FieldDisplayProcessor_OuterContainer($fdp);
    return $fdp;
  }

  /**
   * @CfrPlugin("fullResetDefault", @t("Full reset, default"))
   *
   * @return \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  public static function fullResetDefault() {
    $fdp = new FieldDisplayProcessor_Bare();
    $fdp = new FieldDisplayProcessor_Label($fdp);
    return $fdp;
  }

  /**
   * @CfrPlugin("minimal", @t("Minimal"))
   *
   * @return \Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface
   */
  public static function minimal() {
    return Formula_FieldDisplayProcessor_OuterContainer::create(
      self::fullReset(),
      FALSE)
      ->getValFormula();
  }

  /**
   * @CfrPlugin("fullReset", @t("Full reset"))
   *
   * @return \Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface
   */
  public static function fullReset() {
    return Formula_FieldDisplayProcessor_Label::create(
      self::bare())
      ->getValFormula();
  }

  /**
   * @return \Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface
   */
  private static function bare() {
    return Formula_GroupVal::createEmpty(
      new Formula_ValueProvider_FixedValue(
        new FieldDisplayProcessor_Bare()));
  }

}
