<?php
declare(strict_types=1);

namespace Drupal\renderkit\FieldDisplayProcessor;

use Drupal\renderkit\Formula\Formula_ClassAttribute;
use Drupal\renderkit\Util\UtilBase;
use Ock\CodegenTools\Util\CodeGen;
use Ock\Ock\Attribute\Plugin\OckPluginFormula;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;
use Ock\Ock\Formula\Boolean\Formula_Boolean_YesNo;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Formula\GroupVal\Formula_GroupValInterface;
use Ock\Ock\Formula\Textfield\Formula_Textfield_NoValidation;
use Ock\Ock\Text\Text;

abstract class FieldDisplayProcessor_Misc extends UtilBase {

  /**
   * @return \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  #[OckPluginInstance('minimalDefault', 'Minimal, default')]
  public static function minimalDefault(): FieldDisplayProcessorInterface {
    $fdp = new FieldDisplayProcessor_Bare();
    $fdp = new FieldDisplayProcessor_PrependLabelElement($fdp);
    $fdp = new FieldDisplayProcessor_OuterContainer($fdp);
    return $fdp;
  }

  /**
   * @return \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  #[OckPluginInstance('fullResetDefault', 'Full reset, default')]
  public static function fullResetDefault(): FieldDisplayProcessorInterface {
    $fdp = new FieldDisplayProcessor_Bare();
    $fdp = new FieldDisplayProcessor_PrependLabelElement($fdp);
    return $fdp;
  }

  /**
   * @return \Ock\Ock\Formula\GroupVal\Formula_GroupValInterface
   * @throws \Ock\Ock\Exception\FormulaException
   */
  #[OckPluginFormula(FieldDisplayProcessorInterface::class, 'minimal', 'Minimal')]
  public static function minimal(): Formula_GroupValInterface {
    return self::miscFormula(TRUE, FALSE, TRUE);
  }

  /**
   * @return \Ock\Ock\Formula\GroupVal\Formula_GroupValInterface
   * @throws \Ock\Ock\Exception\FormulaException
   */
  #[OckPluginFormula(FieldDisplayProcessorInterface::class, 'fullReset', 'Full reset')]
  public static function fullReset(): Formula_GroupValInterface {
    return self::miscFormula(FALSE, FALSE, TRUE);
  }

  /**
   * @param bool $withOuterWrapper
   * @param bool $withOuterWrapperClasses
   * @param bool $withLabelOptions
   *
   * @return \Ock\Ock\Formula\GroupVal\Formula_GroupValInterface
   * @throws \Ock\Ock\Exception\FormulaException
   */
  public static function miscFormula(
    bool $withOuterWrapper,
    bool $withOuterWrapperClasses,
    bool $withLabelOptions,
  ): Formula_GroupValInterface {
    $builder = Formula::group();
    if ($withOuterWrapperClasses) {
      $builder->add(
        'classes',
        Text::t('Classes'),
        new Formula_ClassAttribute(),
      );
    }
    if ($withLabelOptions) {
      $builder->add(
        'lb-col',
        Text::t('Show label colon'),
        new Formula_Boolean_YesNo(),
      );
      $builder->add(
        'label',
        Text::t('Custom label'),
        new Formula_Textfield_NoValidation(),
      );
    }
    return $builder->generate(function (array $itemsPhp) use (
      $withOuterWrapper,
      $withOuterWrapperClasses,
      $withLabelOptions,
    ): string {
      $php = CodeGen::phpConstruct(FieldDisplayProcessor_Bare::class);
      if ($withLabelOptions) {
        $php = CodeGen::phpConstruct(FieldDisplayProcessor_PrependLabelElement::class, [
          $php,
          $itemsPhp['lb-col'],
        ]);
        // @todo Give access to original configuration values.
        // @todo Also check NULL and null?
        if ($itemsPhp['label'] !== "''") {
          $php = CodeGen::phpConstruct(FieldDisplayProcessor_CustomLabel::class, [
            $php,
            $itemsPhp['label'],
          ]);
        }
      }
      if ($withOuterWrapper) {
        $php = CodeGen::phpConstruct(FieldDisplayProcessor_OuterContainer::class, [
          $php,
          $withOuterWrapperClasses ? $itemsPhp['classes'] : '[]',
        ]);
      }
      return $php;
    });
  }

}
