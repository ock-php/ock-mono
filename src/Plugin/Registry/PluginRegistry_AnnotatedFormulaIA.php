<?php

declare(strict_types=1);

namespace Donquixote\Ock\Plugin\Registry;

use Donquixote\Ock\AnnotatedFormula\IA\AnnotatedFormulaIAInterface;
use Donquixote\Ock\Exception\PluginListException;
use Donquixote\Ock\Plugin\Plugin;
use Donquixote\Ock\Text\Text;

class PluginRegistry_AnnotatedFormulaIA implements PluginRegistryInterface {

  const KEYS_TO_REMOVE = [
    TRUE, 'id' => TRUE,
    TRUE, 'label' => TRUE,
    'description' => TRUE,
  ];

  /**
   * @var \Donquixote\Ock\AnnotatedFormula\IA\AnnotatedFormulaIAInterface
   */
  private AnnotatedFormulaIAInterface $annotatedFormulaIA;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\AnnotatedFormula\IA\AnnotatedFormulaIAInterface $annotatedFormulaIA
   */
  public function __construct(AnnotatedFormulaIAInterface $annotatedFormulaIA) {
    $this->annotatedFormulaIA = $annotatedFormulaIA;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginss(): array {
    $pluginss = [];
    /** @var \Donquixote\Ock\AnnotatedFormula\AnnotatedFormulaInterface $annotated_formula */
    foreach ($this->annotatedFormulaIA as $annotated_formula) {
      $info = $annotated_formula->getInfo();
      $id = $info['id'] ?? $info[0] ?? NULL;
      if ($id === NULL) {
        throw new PluginListException('Missing id in plugin declaration.');
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
