<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Summarizer;

use Donquixote\ObCK\Formula\Primitive\Formula_PrimitiveInterface;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;

/**
 * @STA
 */
class Summarizer_Primitive implements SummarizerInterface {

  /**
   * @var \Donquixote\ObCK\Formula\Primitive\Formula_PrimitiveInterface
   */
  private Formula_PrimitiveInterface $formula;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Formula\Primitive\Formula_PrimitiveInterface $formula
   */
  public function __construct(Formula_PrimitiveInterface $formula) {
    $this->formula = $formula;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {
    $type = gettype($conf);
    if (!in_array($type, $this->formula->getAllowedTypes())) {
      return Text::builder()
        ->replaceS('@expected', implode('|',
          $this->formula->getAllowedTypes()))
        ->replaceS('@found', $type)
        ->t('Incompatible type: Expected @expected, found @found');
    }
    return Text::s(var_export($conf, TRUE));
  }
}
