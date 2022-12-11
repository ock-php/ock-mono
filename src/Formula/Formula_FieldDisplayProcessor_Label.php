<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Exception\EvaluatorException;
use Donquixote\Ock\Formula\Boolean\Formula_Boolean_YesNo;
use Donquixote\Ock\Formula\Group\Formula_Group_V2VDecoratorBase;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\Ock\Formula\Textfield\Formula_Textfield_NoValidation;
use Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessor_Label;
use Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface;

class Formula_FieldDisplayProcessor_Label extends Formula_Group_V2VDecoratorBase {

  /**
   * @param \Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface $decoratedValFormula
   *
   * @return self
   */
  public static function create(Formula_GroupValInterface $decoratedValFormula): self {
    return new self(
      $decoratedValFormula->getDecorated(),
      $decoratedValFormula->getV2V());
  }

  /**
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface[]
   */
  public function getItemFormulas(): array {
    $formulas = parent::getItemFormulas();
    $formulas['lb-col'] = new Formula_Boolean_YesNo();
    $formulas['label'] = new Formula_Textfield_NoValidation();
    return $formulas;
  }

  /**
   * @return string[]
   */
  public function getLabels(): array {
    $labels = parent::getLabels();
    $labels['lb-col'] = t('Hide label colon');
    $labels['label'] = t('Label');
    return $labels;
  }

  /**
   * @param string[] $itemsPhp
   *
   * @return string
   *
   * @throws \Donquixote\Ock\Exception\GeneratorException
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {

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
