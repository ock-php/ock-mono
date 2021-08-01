<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Plugin;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;

class Plugin {

  /**
   * @var \Donquixote\OCUI\Text\TextInterface|null
   */
  private $label;

  /**
   * @var \Donquixote\OCUI\Text\TextInterface|null
   */
  private $description;

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  private $formula;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Text\TextInterface|null $label
   * @param \Donquixote\OCUI\Text\TextInterface|null $description
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   */
  public function __construct(?TextInterface $label, ?TextInterface $description, FormulaInterface $formula) {
    $this->label = $label;
    $this->description = $description;
    $this->formula = $formula;
  }

  /**
   * Gets a label for the plugin.
   *
   * @return \Donquixote\OCUI\Text\TextInterface|null
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
   * @return \Donquixote\OCUI\Text\TextInterface|null
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
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function getFormula(): FormulaInterface {
    return $this->formula;
  }

}
