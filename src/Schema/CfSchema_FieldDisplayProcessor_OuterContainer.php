<?php

namespace Drupal\renderkit\Schema;

use Donquixote\Cf\Exception\EvaluatorException;
use Donquixote\Cf\Schema\Group\CfSchema_Group_V2VDecoratorBase;
use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface;
use Donquixote\Cf\V2V\Group\V2V_GroupInterface;
use Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessor_OuterContainer;
use Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface;

class CfSchema_FieldDisplayProcessor_OuterContainer extends CfSchema_Group_V2VDecoratorBase {

  /**
   * @var bool
   */
  private $withClassesOption;

  /**
   * @param \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface $decoratedValSchema
   * @param bool $withClassesOption
   *
   * @return self
   */
  public static function create(
    CfSchema_GroupValInterface $decoratedValSchema,
    $withClassesOption
  ) {
    return new self(
      $decoratedValSchema->getDecorated(),
      $decoratedValSchema->getV2V(),
      $withClassesOption);
  }

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $decoratedSchema
   * @param \Donquixote\Cf\V2V\Group\V2V_GroupInterface $decoratedV2V
   * @param bool $withClassesOption
   */
  public function __construct(
    CfSchema_GroupInterface $decoratedSchema,
    V2V_GroupInterface $decoratedV2V,
    $withClassesOption
  ) {
    parent::__construct($decoratedSchema, $decoratedV2V);
    $this->withClassesOption = $withClassesOption;
  }

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface[]
   */
  public function getItemSchemas() {

    $schemas = parent::getItemSchemas();

    if ($this->withClassesOption) {
      $schemas['classes'] = new CfSchema_ClassAttribute();
    }

    return $schemas;
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
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function valuesGetValue(array $values) {

    $fdp = parent::valuesGetValue($values);

    if (!$fdp instanceof FieldDisplayProcessorInterface) {
      throw new EvaluatorException("Expected a FieldDisplayProcessorInterface object.");
    }

    $fdp = new FieldDisplayProcessor_OuterContainer($fdp);

    if ($this->withClassesOption && [] !== $values['classes']) {
      $fdp = $fdp->withAdditionalClasses($values['classes']);
    }

    return $fdp;
  }

  /**
   * @param string[] $itemsPhp
   *
   * @return string
   */
  public function itemsPhpGetPhp(array $itemsPhp) {

    $php = parent::itemsPhpGetPhp($itemsPhp);

    $php = "new \\" . FieldDisplayProcessor_OuterContainer::class . "(\n$php)";

    if (1
      && $this->withClassesOption
      && '[]' !== $itemsPhp['classes']
      && 'array()' !== $itemsPhp['classes']
    ) {
      $php = "($php)\n->withAdditionalClasses($itemsPhp[classes])";
    }

    return $php;
  }
}
