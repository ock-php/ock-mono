<?php

declare(strict_types=1);

namespace Drupal\ock\Formator\Controlling;

use Drupal\Component\Render\MarkupInterface;

interface ControllingFormatorInterface {

  /**
   * @param mixed $conf
   * @param \Drupal\Component\Render\MarkupInterface|string|null $label
   * @param array $ajax
   *
   * @return array
   */
  public function buildControllingSubform(mixed $conf, MarkupInterface|string|null $label, array $ajax): array;

}
