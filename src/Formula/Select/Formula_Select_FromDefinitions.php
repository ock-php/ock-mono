<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Select;

use Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\ObCK\Text\TextInterface;
use Donquixote\ObCK\Translator\Lookup\TranslatorLookup_Passthru;
use Donquixote\ObCK\Translator\Translator;

class Formula_Select_FromDefinitions extends Formula_Select_BufferedBase {

  /**
   * @var array[]
   */
  private $definitions;

  /**
   * @var \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGroupLabel;

  /**
   * Constructor.
   *
   * @param array[] $definitions
   * @param \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
   */
  public function __construct(
    array $definitions,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGroupLabel
  ) {
    $this->definitions = $definitions;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGroupLabel = $definitionToGroupLabel;
  }

  /**
   * {@inheritdoc}
   */
  protected function initialize(array &$grouped_options, array &$group_labels): void {

    // Use a simple translator to build group ids.
    $translator = new Translator(new TranslatorLookup_Passthru());

    foreach ($this->definitions as $id => $definition) {
      $label = $this->definitionToLabel->definitionGetLabel($definition, $id);
      $group_label = $this->definitionToGroupLabel->definitionGetLabel($definition, NULL);
      if ($group_label !== NULL) {
        $group_id = $group_label->convert($translator);
        $groups[$group_id] = $group_label;
        $grouped_options[$group_id][$id] = $label;
      }
      else {
        $grouped_options[''][$id] = $label;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {

    if (!isset($this->definitions[$id])) {
      return NULL;
    }

    return $this->definitionToLabel->definitionGetLabel($this->definitions[$id], $id);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {
    return isset($this->definitions[$id]);
  }
}
