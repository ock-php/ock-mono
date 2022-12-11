<?php
declare(strict_types=1);

namespace Drupal\ock\UI\ParamConverter;

use Drupal\Core\ParamConverter\ParamConverterInterface;
use Symfony\Component\Routing\Route;

abstract class ParamConverterBase implements ParamConverterInterface {

  public const TYPE = NULL;

  /**
   * Determines if the converter applies to a specific route and variable.
   *
   * @param mixed $definition
   *   The parameter definition provided in the route options.
   * @param string $name
   *   The name of the parameter.
   * @param \Symfony\Component\Routing\Route $route
   *   The route to consider attaching to.
   *
   * @return bool
   *   TRUE if the converter applies to the passed route and parameter, FALSE
   *   otherwise.
   */
  public function applies($definition, $name, Route $route): bool {
    return ($definition['type'] ?? FALSE) === static::TYPE;
  }

}
