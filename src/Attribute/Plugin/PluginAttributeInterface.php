<?php

/**
 * @file
 */

declare(strict_types = 1);

namespace Donquixote\Ock\Attribute\Plugin;

use Donquixote\Ock\Plugin\PluginDeclaration;
use Donquixote\Ock\Text\TextInterface;

interface PluginAttributeInterface {

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return \Donquixote\Ock\Plugin\PluginDeclaration
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   */
  public function onClass(\ReflectionClass $reflectionClass): PluginDeclaration;

  /**
   * @param \ReflectionMethod $reflectionMethod
   *
   * @return \Donquixote\Ock\Plugin\PluginDeclaration
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   */
  public function onMethod(\ReflectionMethod $reflectionMethod): PluginDeclaration;

  /**
   * @return string
   */
  public function getId(): string;

  /**
   * @return TextInterface
   */
  public function getLabel(): TextInterface;

}
