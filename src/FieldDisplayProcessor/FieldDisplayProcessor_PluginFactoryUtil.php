<?php

namespace Drupal\renderkit8\FieldDisplayProcessor;

use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal;
use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProvider_FixedValue;
use Drupal\renderkit8\Schema\CfSchema_FieldDisplayProcessor_Label;
use Drupal\renderkit8\Schema\CfSchema_FieldDisplayProcessor_OuterContainer;
use Drupal\renderkit8\Util\UtilBase;

abstract class FieldDisplayProcessor_PluginFactoryUtil extends UtilBase implements FieldDisplayProcessorInterface {

  /**
   * @CfrPlugin("minimalDefault", @t("Minimal, default"))
   *
   * @return \Drupal\renderkit8\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  public static function minimalDefault() {
    $fdp = self::fullResetDefault();
    $fdp = new FieldDisplayProcessor_OuterContainer($fdp);
    return $fdp;
  }

  /**
   * @CfrPlugin("fullResetDefault", @t("Full reset, default"))
   *
   * @return \Drupal\renderkit8\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  public static function fullResetDefault() {
    $fdp = new FieldDisplayProcessor_Bare();
    $fdp = new FieldDisplayProcessor_Label($fdp);
    return $fdp;
  }

  /**
   * @CfrPlugin("minimal", @t("Minimal"))
   *
   * @return \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface
   */
  public static function minimal() {
    return CfSchema_FieldDisplayProcessor_OuterContainer::create(
      self::fullReset(),
      FALSE)
      ->getValSchema();
  }

  /**
   * @CfrPlugin("fullReset", @t("Full reset"))
   *
   * @return \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface
   */
  public static function fullReset() {
    return CfSchema_FieldDisplayProcessor_Label::create(
      self::bare())
      ->getValSchema();
  }

  /**
   * @return \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface
   */
  private static function bare() {
    return CfSchema_GroupVal::createEmpty(
      new CfSchema_ValueProvider_FixedValue(
        new FieldDisplayProcessor_Bare()));
  }

}
