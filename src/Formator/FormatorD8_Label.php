<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Ock\Formula\Label\Formula_LabelInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

class FormatorD8_Label implements FormatorD8Interface {

  /**
   * @var \Drupal\ock\Formator\FormatorD8Interface
   */
  private $decorated;

  /**
   * @var string
   */
  private $label;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Label\Formula_LabelInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
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
   * @param \Drupal\ock\Formator\FormatorD8Interface $decorated
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
