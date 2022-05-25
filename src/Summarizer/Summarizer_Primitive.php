<?php

declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Ock\Formula\Primitive\Formula_PrimitiveInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;

#[Adapter]
class Summarizer_Primitive implements SummarizerInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\Primitive\Formula_PrimitiveInterface $formula
   */
  public function __construct(
    private readonly Formula_PrimitiveInterface $formula,
  ) {}

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
