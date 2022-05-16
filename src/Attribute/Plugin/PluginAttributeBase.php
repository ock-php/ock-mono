<?php

/**
 * @file
 */

declare(strict_types = 1);

namespace Donquixote\Ock\Attribute\Plugin;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Plugin\Plugin;
use Donquixote\Ock\Plugin\PluginDeclaration;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;

abstract class PluginAttributeBase implements PluginAttributeInterface {

  /**
   * @var string
   */
  private string $id;

  /**
   * @var TextInterface
   */
  private TextInterface $label;

  /**
   * Constructor.
   *
   * @param string $id
   * @param string $label
   * @param bool $translate
   */
  public function __construct(string $id, string $label, bool $translate = TRUE) {
    $this->id = $id;
    $this->label = $translate ? Text::t($label) : Text::s($label);
  }

  /**
   * @return string
   */
  public function getId(): string {
    return $this->id;
  }

  /**
   * @return TextInterface
   */
  public function getLabel(): TextInterface {
    return $this->label;
  }

  /**
   * @param FormulaInterface $formula
   * @param list<string> $types
   *
   * @return PluginDeclaration
   */
  protected function formulaGetNamedPlugin(FormulaInterface $formula, array $types): PluginDeclaration {
    $plugin = new Plugin($this->label, null, $formula, []);
    return new PluginDeclaration($this->id, $types, $plugin);
  }

}
