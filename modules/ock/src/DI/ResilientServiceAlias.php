<?php

declare(strict_types=1);

namespace Drupal\ock\DI;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Alias;

/**
 * Alias that is resilient against Drupal's attempts to be made public.
 */
class ResilientServiceAlias extends Alias {

  /**
   * Creates a new instance as a copy of an existing alias.
   *
   * @param \Symfony\Component\DependencyInjection\Alias $alias
   *   The alias to copy.
   *
   * @return static
   *   New instance.
   */
  public static function fromAlias(Alias $alias): static {
    if ($alias instanceof static) {
      return $alias;
    }
    if ($alias->isDeprecated()) {
      throw new \InvalidArgumentException('Deprecated aliases cannot be copied.');
    }
    if (get_class($alias) !== Alias::class) {
      throw new \InvalidArgumentException('Only instances of ' . Alias::class . ' can be copied.');
    }
    // Extend at your own risk.
    // @phpstan-ignore-next-line
    return new static($alias->__toString(), $alias->isPublic());
  }

  /**
   * Sets whether the alias is public.
   *
   * @param bool $boolean
   *   TRUE if public, FALSE if private.
   *
   * @return $this
   */
  public function setPublic(bool $boolean): static {
    if ($boolean && $this->isPrivate()) {
      $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
      if (($trace[1]['class'] ?? null) === ContainerBuilder::class
        && $trace[1]['function'] === 'setAlias'
      ) {
        // Ignore the attempt from Drupal to make this alias public.
        return $this;
      }
    }
    return parent::setPublic($boolean);
  }

}
