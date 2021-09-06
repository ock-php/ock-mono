<?php
declare(strict_types=1);

namespace Drupal\renderkit\FieldDisplayProcessor;

use Donquixote\ObCK\Formula\Boolean\Formula_Boolean_YesNo;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupVal_Callback;
use Donquixote\ObCK\Formula\Iface\Formula_IfaceWithContext;
use Drupal\renderkit\ListFormat\ListFormatInterface;

class FieldDisplayProcessor_ListFormat implements FieldDisplayProcessorInterface {

  /**
   * @var \Drupal\renderkit\ListFormat\ListFormatInterface
   */
  private $listFormat;

  /**
   * @CfrPlugin("listFormatPlus", "List format +")
   *
   */
  public static function createFormula() {
    return Formula_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'create',
      [
        Formula_IfaceWithContext::create(ListFormatInterface::class),
        new Formula_Boolean_YesNo(),
      ],
      [
        t('List format'),
        t('Add field classes'),
      ]);
  }

  /**
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface $listFormat
   * @param bool $withFieldClasses
   *
   * @return \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  public static function create(ListFormatInterface $listFormat, $withFieldClasses = FALSE) {
    $fieldDisplayProcessor = new self($listFormat);
    if ($withFieldClasses) {
      $fieldDisplayProcessor = new FieldDisplayProcessor_FieldClasses($fieldDisplayProcessor);
    }
    return $fieldDisplayProcessor;
  }

  /**
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface $listFormat
   */
  public function __construct(ListFormatInterface $listFormat) {
    $this->listFormat = $listFormat;
  }

  /**
   * @param array|array[][] $element
   *   Format:
   *     ['#theme' => 'field', '#items' => [..], ..]
   *     $['#items'][$delta] = $item
   *
   * @return array
   */
  public function process(array $element) {

    $builds = [];
    foreach ($element['#items'] as $delta => $item) {
      if (!empty($element[$delta])) {
        $builds[$delta] = $element[$delta];
      }
    }

    return $this->listFormat->buildList($builds);
  }
}
