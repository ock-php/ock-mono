<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Sequence;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\Translator\TranslatorInterface;
use Drupal\Tests\taxonomy\Functional\Rest\TermXmlCookieTest;

class Formula_Sequence_ItemLabelCallback extends Formula_SequenceBase {

  /**
   * @var callable
   */
  private $itemLabelCallback;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $itemFormula
   * @param callable $itemLabelCallback
   */
  public function __construct(FormulaInterface $itemFormula, callable $itemLabelCallback) {
    parent::__construct($itemFormula);
    $this->itemLabelCallback = $itemLabelCallback;
  }

  /**
   * {@inheritdoc}
   */
  public function deltaGetItemLabel(?int $delta): TextInterface {
    $label = \call_user_func($this->itemLabelCallback, $delta);
    if ($label instanceof TextInterface) {
      return $label;
    }
    if (is_string($label)) {
      return Text::s($label);
    }
    return Text::i($delta);
  }
}
