<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Sequence;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;
use Donquixote\ObCK\Translator\TranslatorInterface;
use Drupal\Tests\taxonomy\Functional\Rest\TermXmlCookieTest;

class Formula_Sequence_ItemLabelCallback extends Formula_SequenceBase {

  /**
   * @var callable
   */
  private $itemLabelCallback;

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $itemFormula
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
