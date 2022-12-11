<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Group;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Dynamic\Formula_Dynamic_FormulaCallback;
use Donquixote\Ock\Formula\Dynamic\Formula_Dynamic_ValueCallback;
use Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;

class GroupFormulaBuilder extends GroupValFormulaBuilderBase {

  /**
   * @var \Donquixote\Ock\Core\Formula\FormulaInterface[]
   */
  private array $formulas = [];

  /**
   * @var \Donquixote\Ock\Text\TextInterface[]
   */
  private array $labels = [];

  /**
   * Adds another group option.
   *
   * @param string $key
   * @param \Donquixote\Ock\Text\TextInterface $label
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *
   * @return $this
   */
  public function add(string $key, TextInterface $label, FormulaInterface $formula): static {
    $this->formulas[$key] = $formula;
    $this->labels[$key] = $label;
    return $this;
  }

  /**
   * Adds an optionless group option.
   *
   * @param string $key
   * @param \Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface $formula
   *
   * @return $this
   */
  public function addOptionless(string $key, Formula_OptionlessInterface $formula): static {
    // @todo Option to not add a label for optionless options?
    return $this->add($key, Text::s($key), $formula);
  }

  /**
   * @param string $key
   * @param \Donquixote\Ock\Text\TextInterface $label
   * @param list<string> $keys
   * @param callable(mixed...): \Donquixote\Ock\Core\Formula\FormulaInterface $callback
   *
   * @return $this
   *
   * @todo Can the label be dynamic too?
   */
  public function addDynamicFormula(string $key, TextInterface $label, array $keys, callable $callback): static {
    return $this->add(
      $key,
      $label,
      new Formula_Dynamic_FormulaCallback($keys, $callback),
    );
  }

  /**
   * @param string $key
   * @param list<string> $sourceKeys
   * @param callable(mixed...): mixed $valueCallback
   *
   * @return $this
   */
  public function addDynamicValue(string $key, array $sourceKeys, callable $valueCallback): static {
    return $this->add(
      $key,
      Text::s($key),
      new Formula_Dynamic_ValueCallback($sourceKeys, $valueCallback),
    );
  }

  /**
   * @param list<string> $keys
   * @param list<string> $sourceKeys
   * @param callable(mixed...): array $multipleValueCallback
   *
   * @return $this
   */
  public function addDynamicValues(array $keys, array $sourceKeys, callable $multipleValueCallback): static {
    foreach ($keys as $i => $key) {
      $this->add(
        $key,
        Text::s($key),
        new Formula_Dynamic_ValueCallback(
          $keys,
          fn (array $sourceValues) => $multipleValueCallback($sourceValues)[$i],
        ),
      );
    }
    return $this;
  }

  /**
   * @return \Donquixote\Ock\Formula\Group\Formula_GroupInterface
   */
  public function buildGroupFormula(): Formula_GroupInterface {
    return new Formula_Group($this->formulas, $this->labels);
  }

  /**
   * {@inheritdoc}
   */
  protected function addPartial(string $key, V2V_GroupInterface $v2v): GroupValFormulaBuilder {
    return (new GroupValFormulaBuilder($this->buildGroupFormula()))
      ->addPartial($key, $v2v);
  }

  /**
   * {@inheritdoc}
   */
  protected function decorateV2V(?V2V_GroupInterface $v2v): ?V2V_GroupInterface {
    return $v2v;
  }

  /**
   * {@inheritdoc}
   */
  protected function getGroupFormula(): Formula_GroupInterface {
    return $this->buildGroupFormula();
  }

}
