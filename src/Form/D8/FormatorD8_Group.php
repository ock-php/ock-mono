<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D8;

use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\StaUtil;

class FormatorD8_Group implements FormatorD8Interface {

  /**
   * @var \Donquixote\Cf\Form\D8\FormatorD8Interface[]
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
   * @return \Donquixote\Cf\Form\D8\FormatorD8_Group|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_GroupInterface $schema, SchemaToAnythingInterface $schemaToAnything) {

    if (NULL === $itemFormators = StaUtil::getMultiple(
      $schema->getItemSchemas(),
      $schemaToAnything,
      FormatorD8Interface::class)
    ) {
      return NULL;
    }

    return new self($itemFormators, $schema->getLabels());
  }

  /**
   * @param \Donquixote\Cf\Form\D8\FormatorD8Interface[] $itemFormators
   * @param string[] $labels
   */
  public function __construct(array $itemFormators, array $labels) {
    $this->itemFormators = $itemFormators;
    $this->labels = $labels;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

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

      $form[$key] = $itemFormator->confGetD8Form($itemConf, $itemLabel);
    }

    $form['#attached']['library'][] = 'faktoria/form';

    return $form;
  }
}
