<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\StaUtil;

class FormatorD7_Group implements FormatorD7Interface {

  /**
   * @var \Donquixote\Cf\Form\D7\FormatorD7Interface[]
   */
  private $itemFormators;

  /**
   * @var string[]
   */
  private $labels;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\FormatorD7_Group|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_GroupInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?FormatorD7_Group {

    if (NULL === $itemFormators = StaUtil::getMultiple(
      $schema->getItemSchemas(),
      $schemaToAnything,
      FormatorD7Interface::class)
    ) {
      return NULL;
    }

    return new self($itemFormators, $schema->getLabels());
  }

  /**
   * @param \Donquixote\Cf\Form\D7\FormatorD7Interface[] $itemFormators
   * @param string[] $labels
   */
  public function __construct(array $itemFormators, array $labels) {
    $this->itemFormators = $itemFormators;
    $this->labels = $labels;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD7Form($conf, ?string $label): array {

    if (!\is_array($conf)) {
      $conf = [];
    }

    $form = [];

    if (NULL !== $label && '' !== $label) {
      $form['#title'] = $label;
    }

    foreach ($this->itemFormators as $key => $itemFormator) {

      $itemConf = $conf[$key] ?? null;

      $itemLabel = $this->labels[$key] ?? $key;

      $form[$key] = $itemFormator->confGetD7Form($itemConf, $itemLabel);
    }

    return $form;
  }
}
