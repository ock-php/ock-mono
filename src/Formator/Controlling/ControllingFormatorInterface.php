<?php

declare(strict_types=1);

namespace Drupal\ock\Formator\Controlling;

interface ControllingFormatorInterface {

  /**
   * @param mixed $conf
   * @param \Drupal\Component\Render\MarkupInterface|string|null $label
   * @param array $ajax
   *
   * @return array
   */
  public function buildControllingSubform(mixed $conf, \Drupal\Component\Render\MarkupInterface|string|null $label, array $ajax): array;

}
