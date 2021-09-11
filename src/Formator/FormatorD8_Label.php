<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Donquixote\ObCK\Formula\Label\Formula_LabelInterface;
use Donquixote\ObCK\Incarnator\IncarnatorInterface;

class FormatorD8_Label implements FormatorD8Interface {

  /**
   * @var \Drupal\cu\Formator\FormatorD8Interface
   */
  private $decorated;

  /**
   * @var string
   */
  private $label;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Label\Formula_LabelInterface $formula
   * @param \Donquixote\ObCK\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   *
   * @throws \Donquixote\ObCK\Exception\IncarnatorException
   */
  public static function create(Formula_LabelInterface $formula, IncarnatorInterface $incarnator) {

    if (NULL === $decorated = FormatorD8::fromFormula(
        $formula->getDecorated(),
        $incarnator
      )
    ) {
      return NULL;
    }

    return new self($decorated, $formula->getLabel());
  }

  /**
   * @param \Drupal\cu\Formator\FormatorD8Interface $decorated
   * @param string $label
   */
  public function __construct(FormatorD8Interface $decorated, $label) {
    $this->decorated = $decorated;
    $this->label = $label;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

    return $this->decorated->confGetD8Form($conf, $this->label);
  }
}
