<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Schema\DefaultConf\CfSchema_DefaultConfInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

class FormatorD7_DefaultConf implements FormatorD7Interface {

  /**
   * @var \Donquixote\Cf\Form\D7\FormatorD7Interface
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
  ): ?self {

    $decorated = FormatorD7::fromSchema(
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
   * @param \Donquixote\Cf\Form\D7\FormatorD7Interface $decorated
   * @param mixed $defaultConf
   */
  public function __construct(FormatorD7Interface $decorated, $defaultConf) {
    $this->decorated = $decorated;
    $this->defaultConf = $defaultConf;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD7Form($conf, ?string $label): array {

    if (NULL === $conf) {
      $conf = $this->defaultConf;
    }

    return $this->decorated->confGetD7Form($conf, $label);
  }

}
