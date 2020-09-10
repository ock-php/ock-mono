<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D8;

use Donquixote\Cf\Form\D8\Optionable\OptionableFormatorD8Interface;
use Donquixote\Cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

class FormatorD8_IfaceTagged implements FormatorD8Interface, OptionableFormatorD8Interface {

  /**
   * @var \Donquixote\Cf\Form\D8\FormatorD8_DrilldownSelect
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed
   */
  private $schema;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(
    CfSchema_Neutral_IfaceTransformed $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?FormatorD8_IfaceTagged {
    $decorated = FormatorD8::fromSchema(
      $schema->getDecorated(),
      $schemaToAnything
    );

    if (NULL === $decorated) {
      return NULL;
    }

    if (!$decorated instanceof FormatorD8_DrilldownSelect) {
      return NULL;
    }

    return new self($decorated, $schema);
  }

  /**
   * @param \Donquixote\Cf\Form\D8\FormatorD8_DrilldownSelect $decorated
   * @param \Donquixote\Cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed $schema
   */
  public function __construct(
    FormatorD8_DrilldownSelect $decorated,
    CfSchema_Neutral_IfaceTransformed $schema
  ) {
    $this->decorated = $decorated;
    $this->schema = $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionalFormator(): ?FormatorD8Interface {

    if (NULL === $decorated = $this->decorated->getOptionalFormator()) {
      return NULL;
    }

    if (!$decorated instanceof FormatorD8_DrilldownSelect) {
      return NULL;
    }

    return new self($decorated, $this->schema);
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

    $form = $this->decorated->confGetD8Form($conf, $label);

    /* @see \Drupal\faktoria\Element\RenderElement_DrilldownContainer */
    $form['#type'] = 'faktoria_drilldown_container';
    $form['#faktoria_interface'] = $this->schema->getInterface();
    $form['#faktoria_context'] = $this->schema->getContext();

    $form['#attached']['library'][] = 'faktoria/drilldown-tools';

    return $form;
  }
}
