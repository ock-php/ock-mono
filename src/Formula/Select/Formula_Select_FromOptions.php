<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Select;

use Donquixote\ObCK\Formula\Select\Option\SelectOptionInterface;
use Donquixote\ObCK\Text\TextInterface;
use Donquixote\ObCK\Translator\Translator;

class Formula_Select_FromOptions extends Formula_Select_BufferedBase {

  /**
   * @var \Donquixote\ObCK\Formula\Select\Option\SelectOptionInterface[]
   */
  private $options;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Formula\Select\Option\SelectOptionInterface[] $options
   */
  public function __construct(array $options) {
    self::validateOptions(...array_values($options));
    $this->options = $options;
  }

  /**
   * @param \Donquixote\ObCK\Formula\Select\Option\SelectOptionInterface ...$options
   */
  private static function validateOptions(SelectOptionInterface ...$options): void {}

  /**
   * {@inheritdoc}
   */
  protected function initialize(array &$grouped_options, array &$group_labels): void {

    // Use a simple translator to build group ids.
    $translator = Translator::passthru();

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
