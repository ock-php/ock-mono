<?php

namespace Drupal\renderkit\FieldDisplayProcessor;

use Drupal\cfrapi\CfrGroupSchema\CfrGroupSchema_FixedValue;
use Drupal\cfrapi\Configurator\Group\Configurator_CfrGroupSchema;
use Drupal\renderkit\CfrGroupSchema\CfrGroupSchema_FieldDisplayProcessor_Label;
use Drupal\renderkit\CfrGroupSchema\CfrGroupSchema_FieldDisplayProcessor_OuterContainer;
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
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function minimal() {
    return new Configurator_CfrGroupSchema(self::gs_minimal());
  }

  /**
   * @CfrPlugin("fullReset", @t("Full reset"))
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function fullReset() {
    return new Configurator_CfrGroupSchema(self::gs_fullReset());
  }

  /**
   * @return \Drupal\cfrapi\CfrGroupSchema\CfrGroupSchemaInterface
   */
  private static function gs_minimal() {
    return new CfrGroupSchema_FieldDisplayProcessor_OuterContainer(
      self::gs_fullReset(), FALSE);
  }

  /**
   * @return \Drupal\cfrapi\CfrGroupSchema\CfrGroupSchemaInterface
   */
  private static function gs_fullReset() {
    return new CfrGroupSchema_FieldDisplayProcessor_Label(self::gs_bare());
  }

  /**
   * @return \Drupal\cfrapi\CfrGroupSchema\CfrGroupSchemaInterface
   */
  private static function gs_bare() {
    return new CfrGroupSchema_FixedValue(new FieldDisplayProcessor_Bare());
  }

}
