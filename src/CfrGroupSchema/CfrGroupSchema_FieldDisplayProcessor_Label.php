<?php

namespace Drupal\renderkit\CfrGroupSchema;

use Drupal\cfrapi\CfrGroupSchema\CfrGroupSchema_DecoratorBase;
use Drupal\cfrapi\Configurator\Bool\Configurator_Checkbox;
use Drupal\cfrapi\Configurator\Configurator_Textfield;
use Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessor_Label;
use Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface;

class CfrGroupSchema_FieldDisplayProcessor_Label extends CfrGroupSchema_DecoratorBase {

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface[]
   */
  public function getConfigurators() {
    $configurators = parent::getConfigurators();
    $configurators['lb-col'] = new Configurator_Checkbox();
    $configurators['label'] = new Configurator_Textfield(FALSE);
    return $configurators;
  }

  /**
   * @return string[]
   */
  public function getLabels() {
    $labels = parent::getLabels();
    $labels['lb-col'] = t('Hide label colon');
    $labels['label'] = t('Label');
    return $labels;
  }

  /**
   * @param mixed[] $values
   *   Values returned from group configurators.
   *
   * @return mixed
   */
  public function valuesGetValue(array $values) {

    $fdp = parent::valuesGetValue($values);

    if (!$fdp instanceof FieldDisplayProcessorInterface) {
      return $fdp;
    }

    $fdp = new FieldDisplayProcessor_Label($fdp);

    if ($values['lb-col']) {
      $fdp = $fdp->withoutLabelColon();
    }

    if ('' !== $values['label']) {
      $fdp = $fdp->withCustomLabel($values['label']);
    }

    return $fdp;
  }

}
