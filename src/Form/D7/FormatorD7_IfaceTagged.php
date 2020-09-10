<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Form\D7\Optionable\OptionableFormatorD7Interface;
use Donquixote\Cf\Form\D7\Util\D7FormSTAUtil;
use Donquixote\Cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

class FormatorD7_IfaceTagged implements FormatorD7Interface, OptionableFormatorD7Interface {

  /**
   * @var \Donquixote\Cf\Form\D7\FormatorD7_DrilldownSelect
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
  ): ?FormatorD7_IfaceTagged {
    $decorated = D7FormSTAUtil::formator(
      $schema->getDecorated(),
      $schemaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    if (!$decorated instanceof FormatorD7_DrilldownSelect) {
      return NULL;
    }

    return new self($decorated, $schema);
  }

  /**
   * @param \Donquixote\Cf\Form\D7\FormatorD7_DrilldownSelect $decorated
   * @param \Donquixote\Cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed $schema
   */
  public function __construct(
    FormatorD7_DrilldownSelect $decorated,
    CfSchema_Neutral_IfaceTransformed $schema
  ) {
    $this->decorated = $decorated;
    $this->schema = $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionalFormator(): ?FormatorD7Interface {

    if (NULL === $decorated = $this->decorated->getOptionalFormator()) {
      return NULL;
    }

    if (!$decorated instanceof FormatorD7_DrilldownSelect) {
      return NULL;
    }

    return new self($decorated, $this->schema);
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD7Form($conf, ?string $label): array {

    $form = $this->decorated->confGetD7Form($conf, $label);

    /* @see cfrplugin_element_info() */
    $form['#type'] = 'cfrplugin_drilldown_container';
    $form['#cfrplugin_interface'] = $this->schema->getInterface();
    $form['#cfrplugin_context'] = $this->schema->getContext();

    return $form;
  }
}
