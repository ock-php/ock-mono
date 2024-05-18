<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\Exception\AdapterException;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\Egg\Egg\Egg_Construct;
use Ock\Egg\Egg\EggInterface;

class SpecificAdapter_Construct implements SpecificAdapterInterface {

  /**
   * Constructor.
   *
   * @param class-string $class
   * @param bool $hasUniversalAdapterParameter
   * @param list<mixed> $moreArgs
   */
  public function __construct(
    private readonly string $class,
    private readonly bool $hasUniversalAdapterParameter,
    private readonly array $moreArgs,
  ) {}

  /**
   * @param class-string $class
   * @param bool $hasUniversalAdapterParameter
   * @param list<\Ock\Egg\Egg\EggInterface|mixed> $moreArgEggs
   *
   * @return \Ock\Egg\Egg\EggInterface<self>
   */
  public static function ctv(
    string $class,
    bool $hasUniversalAdapterParameter,
    array $moreArgEggs,
  ): EggInterface {
    return new Egg_Construct(self::class, [
      $class,
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
    $args = [$adaptee];
    if ($this->hasUniversalAdapterParameter) {
      $args[] = $universalAdapter;
    }
    try {
      return new ($this->class)(...$args, ...$this->moreArgs);
    }
    catch (\Throwable $e) {
      throw new AdapterException($e->getMessage(), 0, $e);
    }
  }

}
