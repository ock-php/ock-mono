<?php
declare(strict_types=1);

namespace Drupal\ock\RouteHelper;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

abstract class ClassRouteHelperBase implements ClassRouteHelperInterface {

  /**
   * @var array
   */
  private $routeParameters;

  /**
   * @var string
   */
  private $methodName;

  /**
   * Constructor.
   *
   * @param array $routeParameters
   * @param string $suffix
   */
  public function __construct(array $routeParameters, $suffix) {
    $this->routeParameters = $routeParameters;
    $this->methodName = $suffix;
  }

  /**
   * @param string $method_name
   *
   * @return static
   */
  public function subpage(string $method_name): self {
    $clone = clone $this;
    $clone->methodName = $method_name;
    return $clone;
  }

  /**
   * @param string|\Drupal\Component\Render\MarkupInterface $text
   * @param array $options
   *
   * @return \Drupal\Core\Link
   */
  public function link($text, array $options = []): Link {
    assert(is_string($text)
      || $text instanceof MarkupInterface);
    return Link::fromTextAndUrl(
      $text,
      $this->url( $options));
  }

  /**
   * @param array $options
   *
   * @return \Drupal\Core\Url
   */
  public function url(array $options = []): Url {

    return new Url(
      $this->routeName(),
      $this->routeParameters,
      $options);
  }

  /**
   * @return string
   */
  protected function getMethodName(): string {
    return $this->methodName;
  }
}
