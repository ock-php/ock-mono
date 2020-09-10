<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D8;

use Donquixote\Cf\Schema\Label\CfSchema_LabelInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

class FormatorD8_Label implements FormatorD8Interface {

  /**
   * @var \Donquixote\Cf\Form\D8\FormatorD8Interface
   */
  private $decorated;

  /**
   * @var string
   */
  private $label;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Label\CfSchema_LabelInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_LabelInterface $schema, SchemaToAnythingInterface $schemaToAnything) {

    if (NULL === $decorated = FormatorD8::fromSchema(
        $schema->getDecorated(),
        $schemaToAnything
      )
    ) {
      return NULL;
    }

    return new self($decorated, $schema->getLabel());
  }

  /**
   * @param \Donquixote\Cf\Form\D8\FormatorD8Interface $decorated
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
