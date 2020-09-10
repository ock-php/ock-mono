<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D8;

use Donquixote\Cf\Schema\DefaultConf\CfSchema_DefaultConfInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

class FormatorD8_DefaultConf implements FormatorD8Interface {

  /**
   * @var \Donquixote\Cf\Form\D8\FormatorD8Interface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $defaultConf;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\DefaultConf\CfSchema_DefaultConfInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(
    CfSchema_DefaultConfInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?FormatorD8_DefaultConf {

    $decorated = FormatorD8::fromSchema(
      $schema->getDecorated(),
      $schemaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    return new self(
      $decorated,
      $schema->getDefaultConf());
  }

  /**
   * @param \Donquixote\Cf\Form\D8\FormatorD8Interface $decorated
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
