<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Group\Formula_Group_V2VDecoratorBase;
use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;
use Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessor_OuterContainer;

class Formula_FieldDisplayProcessor_OuterContainer extends Formula_Group_V2VDecoratorBase {

  /**
   * @param \Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface $decoratedValFormula
   * @param bool $withClassesOption
   *
   * @return self
   */
  public static function create(
    Formula_GroupValInterface $decoratedValFormula,
    bool $withClassesOption
  ): self {
    return new self(
      $decoratedValFormula->getDecorated(),
      $decoratedValFormula->getV2V(),
      $withClassesOption);
  }

  /**
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $decoratedFormula
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $decoratedV2V
   * @param bool $withClassesOption
   */
  public function __construct(
    Formula_GroupInterface $decoratedFormula,
    V2V_GroupInterface $decoratedV2V,
    private readonly bool $withClassesOption
  ) {
    parent::__construct($decoratedFormula, $decoratedV2V);
  }

  /**
   * {@inheritdoc}
   */
  public function getItemFormulas(): array {

    $formulas = parent::getItemFormulas();

    if ($this->withClassesOption) {
      $formulas['classes'] = new Formula_ClassAttribute();
    }

    return $formulas;
  }

  /**
   * {@inheritdoc}
   */
  public function getLabels(): array {

    $labels = parent::getLabels();

    if ($this->withClassesOption) {
      $labels['classes'] = t('Additional classes');
    }

    return $labels;
  }

  /**
   * {@inheritdoc}
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
