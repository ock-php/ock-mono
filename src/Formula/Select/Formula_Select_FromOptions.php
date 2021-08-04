<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select;

use Donquixote\OCUI\Formula\Drilldown\Option\DrilldownOptionInterface;
use Donquixote\OCUI\Formula\Select\Option\SelectOptionInterface;
use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\TextToMarkup\TextToMarkupInterface;
use Donquixote\OCUI\Translator\Lookup\TranslatorLookup_Passthru;
use Donquixote\OCUI\Translator\Translator;

class Formula_Select_FromOptions extends Formula_Select_BufferedBase {

  /**
   * @var \Donquixote\OCUI\Formula\Select\Option\SelectOptionInterface[]
   */
  private $options;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Formula\Select\Option\SelectOptionInterface[] $options
   */
  public function __construct(array $options) {
    self::validateOptions(...array_values($options));
    $this->options = $options;
  }

  /**
   * @param \Donquixote\OCUI\Formula\Select\Option\SelectOptionInterface ...$options
   */
  private static function validateOptions(SelectOptionInterface ...$options): void {}

  /**
   * {@inheritdoc}
   */
  protected function initialize(array &$grouped_options, array &$group_labels): void {

    // Use a simple translator to build group ids.
    $translator = new Translator(new TranslatorLookup_Passthru());

    foreach ($this->options as $id => $option) {
      $label = $option->getLabel();
      $group_label = $option->getGroupLabel();
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
    if (!isset($this->options[$id])) {
      return NULL;
    }
    return $this->options[$id]->getLabel();
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {
    return isset($this->options[$id]);
  }

}
