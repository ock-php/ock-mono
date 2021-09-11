<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Donquixote\ObCK\Formula\DefaultConf\Formula_DefaultConfInterface;
use Donquixote\ObCK\Incarnator\IncarnatorInterface;

class FormatorD8_DefaultConf implements FormatorD8Interface {

  /**
   * @var \Drupal\cu\Formator\FormatorD8Interface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $defaultConf;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\DefaultConf\Formula_DefaultConfInterface $formula
   * @param \Donquixote\ObCK\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   *
   * @throws \Donquixote\ObCK\Exception\IncarnatorException
   */
  public static function create(
    Formula_DefaultConfInterface $formula,
    IncarnatorInterface $incarnator
  ): ?FormatorD8_DefaultConf {

    $decorated = FormatorD8::fromFormula(
      $formula->getDecorated(),
      $incarnator);

    if (NULL === $decorated) {
      return NULL;
    }

    return new self(
      $decorated,
      $formula->getDefaultConf());
  }

  /**
   * @param \Drupal\cu\Formator\FormatorD8Interface $decorated
   * @param mixed $defaultConf
   */
  public function __construct(FormatorD8Interface $decorated, $defaultConf) {
    $this->decorated = $decorated;
    $this->defaultConf = $defaultConf;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

    if (NULL === $conf) {
      $conf = $this->defaultConf;
    }

    return $this->decorated->confGetD8Form($conf, $label);
  }

}
