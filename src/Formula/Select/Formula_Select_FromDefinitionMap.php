<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select;

use Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\TextToMarkup\TextToMarkupInterface;
use Donquixote\OCUI\Translator\Lookup\TranslatorLookup_Passthru;
use Donquixote\OCUI\Translator\Translator;

class Formula_Select_FromDefinitionMap extends Formula_Select_BufferedBase {

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGroupLabel;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
   */
  public function __construct(
    DefinitionMapInterface $definitionMap,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGroupLabel
  ) {
    $this->definitionMap = $definitionMap;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGroupLabel = $definitionToGroupLabel;
  }

  protected function initialize(array &$grouped_options, array &$group_labels): void {

    // Use a simple translator to build group ids.
    $translator = new Translator(new TranslatorLookup_Passthru());

    foreach ($this->definitionMap->getDefinitionsById() as $id => $definition) {
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
    if (NULL === $definition = $this->definitionMap->idGetDefinition($id)) {
      return NULL;
    }
    return $this->definitionToLabel->definitionGetLabel($definition, $id);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {
    return NULL !== $this->definitionMap->idGetDefinition($id);
  }

}
