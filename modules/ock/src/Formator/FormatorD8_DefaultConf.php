<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Formula\DefaultConf\Formula_DefaultConfInterface;
use Drupal\Component\Render\MarkupInterface;

class FormatorD8_DefaultConf implements FormatorD8Interface {

  /**
   * @param \Donquixote\Ock\Formula\DefaultConf\Formula_DefaultConfInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   *
   * @return self|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function create(
    Formula_DefaultConfInterface $formula,
    UniversalAdapterInterface $adapter,
  ): ?FormatorD8_DefaultConf {

    $decorated = $adapter->adapt(
      $formula->getDecorated(),
      FormatorD8Interface::class,
    );

    if ($decorated === NULL) {
      return NULL;
    }

    return new self(
      $decorated,
      $formula->getDefaultConf(),
    );
  }

  /**
   * @param \Drupal\ock\Formator\FormatorD8Interface $decorated
   * @param mixed $defaultConf
   */
  public function __construct(private readonly FormatorD8Interface $decorated, private readonly mixed $defaultConf) {
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {

    if (NULL === $conf) {
      $conf = $this->defaultConf;
    }

    return $this->decorated->confGetD8Form($conf, $label);
  }

}
