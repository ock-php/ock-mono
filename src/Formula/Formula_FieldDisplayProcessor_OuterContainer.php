<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Exception\EvaluatorException;
use Donquixote\ObCK\Formula\Group\Formula_Group_V2VDecoratorBase;
use Donquixote\ObCK\Formula\Group\Formula_GroupInterface;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\ObCK\Zoo\V2V\Group\V2V_GroupInterface;
use Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessor_OuterContainer;
use Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface;

class Formula_FieldDisplayProcessor_OuterContainer extends Formula_Group_V2VDecoratorBase {

  /**
   * @var bool
   */
  private $withClassesOption;

  /**
   * @param \Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface $decoratedValFormula
   * @param bool $withClassesOption
   *
   * @return self
   */
  public static function create(
    Formula_GroupValInterface $decoratedValFormula,
    $withClassesOption
  ): self {
    return new self(
      $decoratedValFormula->getDecorated(),
      $decoratedValFormula->getV2V(),
      $withClassesOption);
  }

  /**
   * @param \Donquixote\ObCK\Formula\Group\Formula_GroupInterface $decoratedFormula
   * @param \Donquixote\ObCK\Zoo\V2V\Group\V2V_GroupInterface $decoratedV2V
   * @param bool $withClassesOption
   */
  public function __construct(
    Formula_GroupInterface $decoratedFormula,
    V2V_GroupInterface $decoratedV2V,
    $withClassesOption
  ) {
    parent::__construct($decoratedFormula, $decoratedV2V);
    $this->withClassesOption = $withClassesOption;
  }

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface[]
   */
  public function getItemFormulas(): array {

    $formulas = parent::getItemFormulas();

    if ($this->withClassesOption) {
      $formulas['classes'] = new Formula_ClassAttribute();
    }

    return $formulas;
  }

  /**
   * @return string[]
   */
  public function getLabels(): array {

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
   * @throws \Donquixote\ObCK\Exception\EvaluatorException
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
  public function itemsPhpGetPhp(array $itemsPhp): string {

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
