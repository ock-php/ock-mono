<?php
declare(strict_types=1);

namespace Drupal\controller_attributes;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

abstract class ClassRouteHelperBase implements ClassRouteHelperInterface {

  /**
   * @var string
   */
  private string $methodName;

  /**
   * Constructor.
   *
   * @param array $routeParameters
   * @param string $suffix
   */
  public function __construct(private readonly array $routeParameters, string $suffix) {
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
   * @param \Drupal\Component\Render\MarkupInterface|string $text
   * @param array $options
   *
   * @return \Drupal\Core\Link
   */
  public function link(MarkupInterface|string $text, array $options = []): Link {
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
