<?php

namespace Drupal\renderkit\Schema;

use Donquixote\Cf\Exception\EvaluatorException;
use Donquixote\Cf\Schema\Boolean\CfSchema_Boolean_YesNo;
use Donquixote\Cf\Schema\Group\CfSchema_Group_V2VDecoratorBase;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface;
use Donquixote\Cf\Schema\Textfield\CfSchema_Textfield_NoValidation;
use Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessor_Label;
use Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface;

class CfSchema_FieldDisplayProcessor_Label extends CfSchema_Group_V2VDecoratorBase {

  /**
   * @param \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface $decoratedValSchema
   *
   * @return self
   */
  public static function create(CfSchema_GroupValInterface $decoratedValSchema) {
    return new self(
      $decoratedValSchema->getDecorated(),
      $decoratedValSchema->getV2V());
  }

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface[]
   */
  public function getItemSchemas() {
    $schemas = parent::getItemSchemas();
    $schemas['lb-col'] = new CfSchema_Boolean_YesNo();
    $schemas['label'] = new CfSchema_Textfield_NoValidation();
    return $schemas;
  }

  /**
   * @return string[]
   */
  public function getLabels() {
    $labels = parent::getLabels();
    $labels['lb-col'] = t('Hide label colon');
    $labels['label'] = t('Label');
    return $labels;
  }

  /**
   * @param mixed[] $values
   *
   * @return \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function valuesGetValue(array $values) {

    $fdp = parent::valuesGetValue($values);

    if (!$fdp instanceof FieldDisplayProcessorInterface) {
      throw new EvaluatorException("Expected a FieldDisplayProcessorInterface object.");
    }

    $fdp = new FieldDisplayProcessor_Label($fdp);

    if ($values['lb-col']) {
      $fdp = $fdp->withoutLabelColon();
    }

    if ('' !== $values['label']) {
      $fdp = $fdp->withCustomLabel($values['label']);
    }

    return $fdp;
  }

  /**
   * @param string[] $itemsPhp
   *
   * @return string
   */
  public function itemsPhpGetPhp(array $itemsPhp) {

    $php = parent::itemsPhpGetPhp($itemsPhp);

    $php = 'new \\' . FieldDisplayProcessor_Label::class . "(\n$php)";

    $prepend = '';

    if ('true' === $itemsPhp['lb-col'] || 'TRUE' === $itemsPhp['lb-col']) {
      $prepend .= "\n->withoutLabelColon()";
    }

    if ('""' !== $itemsPhp['label'] && "''" !== $itemsPhp['label']) {
      $prepend .= "\n->withCustomLabel($itemsPhp[label])";
    }

    if ('' !== $prepend) {
      $php = "($php)$prepend";
    }

    return $php;

  }
}
