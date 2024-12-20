<?php

declare(strict_types=1);

namespace Ock\Ock\Formula\SelectOld;

use Ock\Ock\Formula\SelectOld\Option\SelectOptionInterface;
use Ock\Ock\Text\TextInterface;
use Ock\Ock\Translator\Translator;

class Formula_Select_FromOptions extends Formula_Select_BufferedBase {

  /**
   * Constructor.
   *
   * @param \Ock\Ock\Formula\SelectOld\Option\SelectOptionInterface[] $options
   */
  public function __construct(
    private readonly array $options,
  ) {
    self::validateOptions(...$options);
  }

  /**
   * @param \Ock\Ock\Formula\SelectOld\Option\SelectOptionInterface ...$options
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
  public function idGetLabel(string|int $id): ?TextInterface {
    if (!isset($this->options[$id])) {
      return NULL;
    }
    return $this->options[$id]->getLabel();
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(string|int $id): bool {
    return isset($this->options[$id]);
  }

}
