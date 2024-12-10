<?php
declare(strict_types=1);

namespace Drupal\controller_attributes;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Base class for a url builder.
 */
abstract class ClassRouteHelperBase implements ClassRouteHelperInterface {

  /**
   * @var string
   */
  private string $methodName;

  /**
   * Constructor.
   *
   * @param array $routeParameters
   *   Route parameter values.
   * @param string $suffix
   *   Controller method name.
   */
  public function __construct(
    private readonly array $routeParameters,
    string $suffix,
  ) {
    $this->methodName = $suffix;
  }

  /**
   * {@inheritdoc}
   */
  public function subpage(string $method_name): self {
    $clone = clone $this;
    $clone->methodName = $method_name;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function link(MarkupInterface|string $text, array $options = []): Link {
    assert(is_string($text)
      || $text instanceof MarkupInterface);
    return Link::fromTextAndUrl($text, $this->url( $options));
  }

  /**
   * {@inheritdoc}
   */
  public function url(array $options = []): Url {
    return new Url(
      $this->routeName(),
      $this->routeParameters,
      $options,
    );
  }

  /**
   * @return string
   */
  protected function getMethodName(): string {
    return $this->methodName;
  }

}
