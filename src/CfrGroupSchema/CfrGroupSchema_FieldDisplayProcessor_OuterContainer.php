<?php

namespace Drupal\renderkit\CfrGroupSchema;

use Drupal\cfrapi\CfrGroupSchema\CfrGroupSchema_DecoratorBase;
use Drupal\cfrapi\CfrGroupSchema\CfrGroupSchemaInterface;
use Drupal\renderkit\Configurator\Configurator_ClassAttribute;
use Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessor_OuterContainer;
use Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface;

class CfrGroupSchema_FieldDisplayProcessor_OuterContainer extends CfrGroupSchema_DecoratorBase {

  /**
   * @var bool
   */
  private $withClassesOption;

  /**
   * @param \Drupal\cfrapi\CfrGroupSchema\CfrGroupSchemaInterface $decorated
   * @param bool $withClassesOption
   */
  public function __construct(CfrGroupSchemaInterface $decorated, $withClassesOption = TRUE) {
    parent::__construct($decorated);
    $this->withClassesOption = $withClassesOption;
  }

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface[]
   */
  public function getConfigurators() {
    $configurators = parent::getConfigurators();
    if ($this->withClassesOption) {
      $configurators['classes'] = new Configurator_ClassAttribute();
    }
    return $configurators;
  }

  /**
   * @return string[]
   */
  public function getLabels() {
    $labels = parent::getLabels();
    if ($this->withClassesOption) {
      $labels['classes'] = t('Additional classes');
    }
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

    $fdp = new FieldDisplayProcessor_OuterContainer($fdp);

    if ($this->withClassesOption && [] !== $values['classes']) {
      $fdp = $fdp->withAdditionalClasses($values['classes']);
    }

    return $fdp;
  }
}
