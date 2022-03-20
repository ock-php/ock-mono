<?php

/**
 * @file
 */

declare(strict_types = 1);

namespace Donquixote\Ock\Attribute\Plugin;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Plugin\NamedTypedPlugin;
use Donquixote\Ock\Text\TextInterface;

interface PluginAttributeInterface {

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return \Donquixote\Ock\Plugin\NamedTypedPlugin
   */
  public function fromClass(\ReflectionClass $reflectionClass): NamedTypedPlugin;

  /**
   * @param \ReflectionMethod $reflectionMethod
   *
   * @return \Donquixote\Ock\Plugin\NamedTypedPlugin
   */
  public function fromMethod(\ReflectionMethod $reflectionMethod): NamedTypedPlugin;

  /**
   * @return string
   */
  public function getId(): string;

  /**
   * @return TextInterface
   */
  public function getLabel(): TextInterface;

}
