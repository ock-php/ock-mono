<?php

declare(strict_types = 1);

namespace Drupal\ock\Attribute\Routing;

use Symfony\Component\Routing\Route;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RouteActionLink implements RouteModifierInterface {

  /**
   * @var string
   */
  private mixed $title;

  /**
   * @var string[]|string|null
   */
  private mixed $appears_on;

  /**
   * @param array $values
   */
  public function __construct(array $values) {

    if (isset($values['title'])) {
      $this->title = $values['title'];
    }
    elseif (isset($values['value'])) {
      $this->title = $values['value'];
    }
    else {
      throw new \RuntimeException("Action link title is required.");
    }

    if (isset($values['appears_on'])) {
      $this->appears_on = $values['appears_on'];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function modifyRoute(Route $route, \ReflectionMethod|\ReflectionClass $reflector): void {
    $link = [];
    $link['title'] = $this->title;
    if ($this->appears_on !== NULL) {
      $link['appears_on'] = $this->appears_on;
    }
    $route->setOption('_action_link', $link);
  }

}
