<?php

declare(strict_types=1);

namespace Donquixote\Ock\Attribute\Plugin;

use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class OckPlugin {

  /**
   * @var string
   */
  private string $id;

  /**
   * @var TextInterface
   */
  private TextInterface $label;

  private bool $inline;

  private bool $decorator;

  /**
   * Constructor.
   *
   * @param string $id
   * @param string $label
   * @param bool $translate
   * @param bool $inline
   * @param bool $decorator
   */
  public function __construct(string $id, string $label, bool $translate = TRUE, bool $inline = FALSE, bool $decorator = FALSE, bool $adapter = FALSE) {
    $this->id = $id;
    $this->label = $translate ? Text::t($label) : Text::s($label);
    $this->inline = $inline;
    $this->decorator = $decorator;
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
   * @return \Donquixote\Ock\Text\TextInterface|null
   */
  public function getDescription(): ?TextInterface {
    return NULL;
  }

  /**
   * @return array
   */
  public function getOptions(): array {
    return array_filter([
      'inline' => $this->inline,
    ]);
  }

  /**
   * @return bool
   */
  public function isDecorator(): bool {
    return $this->decorator;
  }

}
