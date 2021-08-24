<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Discovery\AnnotatedFactory;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;

interface AnnotatedFactoryInterface {

  /**
   * @param string $prefix
   *
   * @return array|null
   */
  public function createDefinition(string $prefix): ?array;

  /**
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  public function getCallback(): CallbackReflectionInterface;

  /**
   * @return string
   */
  public function getDocComment(): string;

  /**
   * @return string[]
   */
  public function getReturnTypeNames(): array;
}
