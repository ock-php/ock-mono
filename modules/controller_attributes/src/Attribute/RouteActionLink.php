<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes\Attribute;

use Symfony\Component\Routing\Route;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RouteActionLink implements RouteModifierInterface {

  /**
   * Constructor.
   *
   * @param string $title
   *   Title to use for the action link.
   * @param list<string> $appears_on
   *   Routes which the action link appears on.
   *   The spelling (lower snake case) reflects the spelling of the setting in
   *   the respective yaml file.
   */
  public function __construct(
    private readonly string $title,
    private readonly ?array $appears_on = null,
    private readonly ?int $weight = null,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function modifyRoute(Route $route, \ReflectionMethod|\ReflectionClass $reflector): void {
    $link = [
      'title' => $this->title,
    ];
    if ($this->appears_on !== NULL) {
      $link['appears_on'] = $this->appears_on;
    }
    if ($this->weight !== NULL) {
      $link['weight'] = $this->weight;
    }
    $route->setOption('_action_link', $link);
  }

}
