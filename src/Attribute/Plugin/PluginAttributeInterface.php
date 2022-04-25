<?php

/**
 * @file
 */

declare(strict_types = 1);

namespace Donquixote\Ock\Attribute\Plugin;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Plugin\PluginDeclaration;
use Donquixote\Ock\Text\TextInterface;

interface PluginAttributeInterface {

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return \Donquixote\Ock\Plugin\PluginDeclaration
   */
  public function fromClass(\ReflectionClass $reflectionClass): PluginDeclaration;

  /**
   * @param \ReflectionMethod $reflectionMethod
   *
   * @return \Donquixote\Ock\Plugin\PluginDeclaration
   */
  public function fromMethod(\ReflectionMethod $reflectionMethod): PluginDeclaration;

  /**
   * @return string
   */
  public function getId(): string;

  /**
   * @return TextInterface
   */
  public function getLabel(): TextInterface;

}
