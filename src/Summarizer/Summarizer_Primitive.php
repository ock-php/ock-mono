<?php

declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Ock\Formula\Primitive\Formula_PrimitiveInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;

/**
 * @STA
 */
class Summarizer_Primitive implements SummarizerInterface {

  /**
   * @var \Donquixote\Ock\Formula\Primitive\Formula_PrimitiveInterface
   */
  private Formula_PrimitiveInterface $formula;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\Primitive\Formula_PrimitiveInterface $formula
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
