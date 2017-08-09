<?php

namespace Drupal\renderkit8\FieldDisplayProcessor;

use Donquixote\Cf\Schema\Boolean\CfSchema_Boolean_YesNo;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Drupal\renderkit8\ListFormat\ListFormatInterface;

class FieldDisplayProcessor_ListFormat implements FieldDisplayProcessorInterface {

  /**
   * @var \Drupal\renderkit8\ListFormat\ListFormatInterface
   */
  private $listFormat;

  /**
   * @CfrPlugin("listFormatPlus", "List format +")
   *
   */
  public static function createSchema() {
    return CfSchema_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'create',
      [
        CfSchema_IfaceWithContext::create(ListFormatInterface::class),
        new CfSchema_Boolean_YesNo(),
      ],
      [
        t('List format'),
        t('Add field classes'),
      ]);
  }

  /**
   * @param \Drupal\renderkit8\ListFormat\ListFormatInterface $listFormat
   * @param bool $withFieldClasses
   *
   * @return \Drupal\renderkit8\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  public static function create(ListFormatInterface $listFormat, $withFieldClasses = FALSE) {
    $fieldDisplayProcessor = new self($listFormat);
    if ($withFieldClasses) {
      $fieldDisplayProcessor = new FieldDisplayProcessor_FieldClasses($fieldDisplayProcessor);
    }
    return $fieldDisplayProcessor;
  }

  /**
   * @param \Drupal\renderkit8\ListFormat\ListFormatInterface $listFormat
   */
  public function __construct(ListFormatInterface $listFormat) {
    $this->listFormat = $listFormat;
  }

  /**
   * @param array $element
   *   Render array with ['#theme' => 'field', ..]
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
