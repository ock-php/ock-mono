<?php

declare(strict_types=1);

namespace Donquixote\ObCK\InlinePluginList;

use Donquixote\ObCK\Formula\Select\Flat\Formula_FlatSelectInterface;
use Donquixote\ObCK\Formula\Select\Formula_Select_FromFlatSelect;
use Donquixote\ObCK\Formula\Select\Formula_SelectInterface;
use Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProvider_FixedValue;
use Donquixote\ObCK\FormulaBase\FormulaBase_AbstractSelectInterface;
use Donquixote\ObCK\Plugin\Plugin;
use Donquixote\ObCK\Text\Text;

class InlinePluginList_Select implements InlinePluginListInterface {

  /**
   * @var \Donquixote\ObCK\FormulaBase\FormulaBase_AbstractSelectInterface
   */
  private $formula;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Select\Flat\Formula_FlatSelectInterface $formula
   *
   * @return self
   */
  public static function createFlat(Formula_FlatSelectInterface $formula): self {
    return new self(
      new Formula_Select_FromFlatSelect($formula));
  }

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Select\Formula_SelectInterface $formula
   *
   * @return self
   */
  public static function create(Formula_SelectInterface $formula): self {
    return new self($formula);
  }

  /**
   * @param \Donquixote\ObCK\FormulaBase\FormulaBase_AbstractSelectInterface $formula
   */
  public function __construct(FormulaBase_AbstractSelectInterface $formula) {
    $this->formula = $formula;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds(): array {
    $options = $this->formula->getOptions(NULL);
    foreach ($this->formula->getOptGroups() as $group_id => $groupLabel) {
      $options += $this->formula->getOptions($group_id);
    }
    return array_keys($options);
  }

  /**
   * {@inheritdoc}
   */
  public function idGetPlugin(string $id): ?Plugin {
    if (!$this->formula->idIsKnown($id)) {
      return NULL;
    }
    return new Plugin(
      $this->formula->idGetLabel($id) ?? Text::s($id),
      NULL,
      new Formula_ValueProvider_FixedValue($id),
      []);
  }

}
