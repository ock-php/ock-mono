<?php
declare(strict_types=1);

namespace Drupal\controller_attributes;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Url builder for routes based on controller attributes.
 */
interface ClassRouteHelperInterface {

  /**
   * Immutable setter. Sets the controller method name.
   *
   * @param string $method_name
   *   Controller method name.
   *
   * @return static
   *   Modified clone.
   */
  public function subpage(string $method_name): self;

  /**
   * Creates a link object.
   *
   * @param \Drupal\Component\Render\MarkupInterface|string $text
   *   Link text.
   * @param array $options
   *   Options for the url.
   *
   * @return \Drupal\Core\Link
   *   New link object.
   */
  public function link(MarkupInterface|string $text, array $options = []): Link;

  /**
   * Creates a url object.
   *
   * @param array $options
   *   Options for the url.
   *
   * @return \Drupal\Core\Url
   *   New url object.
   */
  public function url(array $options = []): Url;

  /**
   * Gets the route name.
   *
   * @return string
   *   The route name.
   */
  public function routeName(): string;

}
