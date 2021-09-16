<?php

declare(strict_types=1);

namespace Donquixote\Ock\InlinePluginList;

use Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Donquixote\Ock\Formula\Select\Formula_Select_FromFlatSelect;
use Donquixote\Ock\Formula\Select\Formula_SelectInterface;
use Donquixote\Ock\Formula\ValueProvider\Formula_ValueProvider_FixedPhp;
use Donquixote\Ock\FormulaBase\FormulaBase_AbstractSelectInterface;
use Donquixote\Ock\Plugin\Plugin;
use Donquixote\Ock\Text\Text;

class InlinePluginList_Select implements InlinePluginListInterface {

  /**
   * @var \Donquixote\Ock\FormulaBase\FormulaBase_AbstractSelectInterface
   */
  private $formula;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface $formula
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
   * @param \Donquixote\Ock\Formula\Select\Formula_SelectInterface $formula
   *
   * @return self
   */
  public static function create(Formula_SelectInterface $formula): self {
    return new self($formula);
  }

  /**
   * @param \Donquixote\Ock\FormulaBase\FormulaBase_AbstractSelectInterface $formula
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
      Formula_ValueProvider_FixedPhp::fromValueSimple($id),
      []);
  }

}
