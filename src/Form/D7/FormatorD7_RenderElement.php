<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7;

class FormatorD7_RenderElement implements FormatorD7Interface {

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
  public function confGetD7Form($conf, ?string $label): array {

    return $this->element;
  }
}
