<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Plugin;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;

class Plugin {

  /**
   * @var \Donquixote\ObCK\Text\TextInterface|null
   */
  private $label;

  /**
   * @var \Donquixote\ObCK\Text\TextInterface|null
   */
  private $description;

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  private $formula;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Text\TextInterface|null $label
   * @param \Donquixote\ObCK\Text\TextInterface|null $description
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   */
  public function __construct(?TextInterface $label, ?TextInterface $description, FormulaInterface $formula) {
    $this->label = $label;
    $this->description = $description;
    $this->formula = $formula;
  }

  /**
   * Gets a label for the plugin.
   *
   * @return \Donquixote\ObCK\Text\TextInterface|null
   */
  public function getLabel(): ?TextInterface {
    return $this->label;
  }

  public function getLabelOr(string $id): TextInterface {
    return $this->label ?: Text::s($id);
  }

  /**
   * Gets a description for the plugin.
   *
   * @return \Donquixote\ObCK\Text\TextInterface|null
   */
  public function getDescription(): ?TextInterface {
    return $this->description;
  }

  /**
   * Gets the formula for this plugin.
   *
   * The formula defines the available configuration options, and the way that
   * factory code shall be generated for the plugin.
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function getFormula(): FormulaInterface {
    return $this->formula;
  }

}
