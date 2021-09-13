<?php

declare(strict_types=1);

namespace Donquixote\Ock\Plugin;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Text\TextInterface;

class Plugin {

  /**
   * @var \Donquixote\Ock\Text\TextInterface
   */
  private $label;

  /**
   * @var \Donquixote\Ock\Text\TextInterface|null
   */
  private $description;

  /**
   * @var \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  private FormulaInterface $formula;

  /**
   * @var array
   */
  private array $info;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Text\TextInterface $label
   * @param \Donquixote\Ock\Text\TextInterface|null $description
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
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
   * @return \Donquixote\Ock\Text\TextInterface
   */
  public function getLabel(): TextInterface {
    return $this->label;
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
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
   * @return \Donquixote\Ock\Text\TextInterface|null
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
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
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
