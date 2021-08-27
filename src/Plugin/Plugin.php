<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Plugin;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;

class Plugin {

  /**
   * @var \Donquixote\ObCK\Text\TextInterface
   */
  private $label;

  /**
   * @var \Donquixote\ObCK\Text\TextInterface|null
   */
  private $description;

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  private FormulaInterface $formula;

  /**
   * @var array
   */
  private array $info;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Text\TextInterface $label
   * @param \Donquixote\ObCK\Text\TextInterface|null $description
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param array $info
   */
  public function __construct(TextInterface $label, ?TextInterface $description, FormulaInterface $formula, array $info) {
    $this->label = $label;
    $this->description = $description;
    $this->formula = $formula;
    $this->info = $info;
  }

  /**
   * Gets a label for the plugin.
   *
   * @return \Donquixote\ObCK\Text\TextInterface
   */
  public function getLabel(): TextInterface {
    return $this->label;
  }

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   *
   * @return static
   */
  public function withFormula(FormulaInterface $formula): self {
    $clone = clone $this;
    $clone->formula = $formula;
    return $clone;
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

  /**
   * Gets the (reduced) annotation data.
   *
   * @return array
   */
  public function getInfo(): array {
    return $this->info;
  }

  /**
   * Gets a boolean value from the annotation.
   *
   * @param string $key
   *
   * @return bool
   */
  public function is(string $key): bool {
    return !empty($this->info[$key]);
  }

  /**
   * Gets a value from the annotation.
   *
   * @param string $key
   *
   * @return mixed
   */
  public function get(string $key) {
    return $this->info[$key];
  }

}
