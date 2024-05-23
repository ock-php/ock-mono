<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Ock\Ock\Form\Common\FormatorCommonInterface;
use Drupal\Component\Render\MarkupInterface;

interface FormatorD8Interface extends FormatorCommonInterface {

  /**
   * @param mixed $conf
   * @param \Drupal\Component\Render\MarkupInterface|string|null $label
   *
   * @return array
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array;

}
