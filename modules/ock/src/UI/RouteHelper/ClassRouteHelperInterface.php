<?php
declare(strict_types=1);

namespace Drupal\ock\UI\RouteHelper;

use Drupal\Core\Link;
use Drupal\Core\Url;

interface ClassRouteHelperInterface {

  /**
   * @param string $method_name
   *
   * @return static
   */
  public function subpage(string $method_name): self;

  /**
   * @param \Drupal\Component\Render\MarkupInterface|string $text
   * @param array $options
   *
   * @return \Drupal\Core\Link
   */
  public function link(\Drupal\Component\Render\MarkupInterface|string $text, array $options = []): Link;

  /**
   * @param array $options
   *
   * @return \Drupal\Core\Url
   */
  public function url(array $options = []): Url;

  /**
   * @return string
   */
  public function routeName(): string;

}
