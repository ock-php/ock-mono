<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\Egg\Egg\Egg_Construct;
use Ock\Egg\Egg\EggInterface;

class SpecificAdapter_SelfMethod implements SpecificAdapterInterface {

  /**
   * Constructor.
   *
   * @param class-string $class
   * @param string $method
   * @param bool $hasResultTypeParameter
   * @param bool $hasUniversalAdapterParameter
   * @param list<mixed> $moreArgs
   */
  public function __construct(
    private readonly string $class,
    private readonly string $method,
    private readonly bool $hasResultTypeParameter,
    private readonly bool $hasUniversalAdapterParameter,
    private readonly array $moreArgs,
  ) {}

  /**
   * @param class-string $class
   * @param string $method
   * @param bool $hasResultTypeParameter
   * @param bool $hasUniversalAdapterParameter
   * @param list<\Ock\Egg\Egg\EggInterface|mixed> $moreArgEggs
   *
   * @return \Ock\Egg\Egg\EggInterface<self>
   */
  public static function ctv(
    string $class,
    string $method,
    bool $hasResultTypeParameter,
    bool $hasUniversalAdapterParameter,
    array $moreArgEggs,
  ): EggInterface {
    if (!method_exists($class, $method)) {
      throw new \InvalidArgumentException(sprintf(
        'Method not found: %s.',
        $class . '::' . $method . '()',
      ));
    }
    return new Egg_Construct(self::class, [
      $class,
      $method,
      $hasResultTypeParameter,
      $hasUniversalAdapterParameter,
      $moreArgEggs,
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function adapt(
    object $adaptee,
    string $resultType,
    UniversalAdapterInterface $universalAdapter,
  ): ?object {
    if (!$adaptee instanceof $this->class) {
      return null;
    }
    $args = [];
    if ($this->hasResultTypeParameter) {
      $args[] = $resultType;
    }
    if ($this->hasUniversalAdapterParameter) {
      $args[] = $universalAdapter;
    }
    return $adaptee->{$this->method}(...$args, ...$this->moreArgs);
  }

}
