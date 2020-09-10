<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Form\D7\Util\D7FormSTAUtil;
use Donquixote\Cf\Schema\Label\CfSchema_LabelInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

class FormatorD7_Label implements FormatorD7Interface {

  /**
   * @var \Donquixote\Cf\Form\D7\FormatorD7Interface
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
  public static function create(CfSchema_LabelInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?FormatorD7_Label {

    if (NULL === $decorated = D7FormSTAUtil::formator(
        $schema->getDecorated(),
        $schemaToAnything
      )
    ) {
      return NULL;
    }

    return new self($decorated, $schema->getLabel());
  }

  /**
   * @param \Donquixote\Cf\Form\D7\FormatorD7Interface $decorated
   * @param string $label
   */
  public function __construct(FormatorD7Interface $decorated, $label) {
    $this->decorated = $decorated;
    $this->label = $label;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD7Form($conf, ?string $label): array {

    return $this->decorated->confGetD7Form($conf, $this->label);
  }
}
