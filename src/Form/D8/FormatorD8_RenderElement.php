<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Form\D8;

class FormatorD8_RenderElement implements FormatorD8Interface {

  /**
   * @var array
   */
  private $element;

  /**
   * @param array $element
   */
  public function __construct(array $element) {
    $this->element = $element;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

    return $this->element;
  }
}
