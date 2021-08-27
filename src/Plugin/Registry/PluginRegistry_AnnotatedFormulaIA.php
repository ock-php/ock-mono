<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Plugin\Registry;

use Donquixote\ObCK\AnnotatedFormula\IA\AnnotatedFormulaIAInterface;
use Donquixote\ObCK\Plugin\Plugin;
use Donquixote\ObCK\Text\Text;

class PluginRegistry_AnnotatedFormulaIA implements PluginRegistryInterface {

  const KEYS_TO_REMOVE = [
    TRUE, 'id' => TRUE,
    TRUE, 'label' => TRUE,
    'description' => TRUE,
  ];

  /**
   * @var \Donquixote\ObCK\AnnotatedFormula\IA\AnnotatedFormulaIAInterface
   */
  private AnnotatedFormulaIAInterface $annotatedFormulaIA;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\AnnotatedFormula\IA\AnnotatedFormulaIAInterface $annotatedFormulaIA
   */
  public function __construct(AnnotatedFormulaIAInterface $annotatedFormulaIA) {
    $this->annotatedFormulaIA = $annotatedFormulaIA;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginss(): array {
    $pluginss = [];
    /** @var \Donquixote\ObCK\AnnotatedFormula\AnnotatedFormulaInterface $annotated_formula */
    foreach ($this->annotatedFormulaIA as $annotated_formula) {
      $info = $annotated_formula->getInfo();
      $id = $info['id'] ?? $info[0] ?? NULL;
      if ($id === NULL) {
        // @todo Log this.
        continue;
      }
      $pluginss[$annotated_formula->getType()][$id] = new Plugin(
        Text::tOrNull($info['label'] ?? $info[1] ?? NULL) ?? Text::s($id),
        Text::tOrNull($info['description'] ?? NULL),
        $annotated_formula->getFormula(),
        array_diff_key($info, self::KEYS_TO_REMOVE));
    }
    return $pluginss;
  }

}
