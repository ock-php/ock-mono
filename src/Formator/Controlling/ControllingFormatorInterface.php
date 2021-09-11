<?php

declare(strict_types=1);

namespace Drupal\ock\Formator\Controlling;

interface ControllingFormatorInterface {

  /**
   * @param mixed $conf
   * @param string|\Drupal\Component\Render\MarkupInterface|null $label
   * @param array $ajax
   *
   * @return array
   */
  public function buildControllingSubform($conf, $label, array $ajax): array;

}
