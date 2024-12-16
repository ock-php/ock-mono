<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes\Attribute;

use Symfony\Component\Routing\Route;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RouteParameters implements RouteModifierInterface {

  /**
   * @var array[]
   */
  private array $parameters;

  /**
   * Constructor.
   *
   * @param array $values
   */
  public function __construct(array $values) {
    $this->parameters = self::valuesBuildParameters($values);
  }

  /**
   * @param array $values
   *
   * @return array[]
   */
  private static function valuesBuildParameters(array $values): array {
    $parameters = [];
    foreach ($values as $k => $v) {

      if (\is_string($v)) {
        $parameters[$k] = ['type' => $v];
      }
      elseif ([] === $v) {
        // @todo Is this allowed?
        $parameters[$k] = [];
      }
      elseif (\is_array($v)) {
        if (array_keys($v) !== ['type']) {
          throw new \RuntimeException("Parameter array must have only one key, 'type'.");
        }
        $type = $v['type'];
        if (!\is_string($type)) {
          throw new \RuntimeException('Parameter type must be a string.');
        }
        $parameters[$k] = ['type' => $type];
      }
      else {
        throw new \RuntimeException('Parameter must be an array or a string.');
      }
    }

    return $parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function modifyRoute(Route $route, \ReflectionMethod|\ReflectionClass $reflector): void {
    $route->setOption('parameters', $this->parameters);
  }

}
