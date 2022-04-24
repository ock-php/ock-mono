<?php

declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;

#[Adapter]
class Summarizer_Textfield implements SummarizerInterface {

  /**
   * @var \Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface
   */
  private $formula;

  /**
   * @param \Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface $formula
   */
  public function __construct(Formula_TextfieldInterface $formula) {
    $this->formula = $formula;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    if (!\is_string($conf)) {
      return Text::t('Value is not a string.');
    }

    if ([] !== $errors = $this->formula->textGetValidationErrors($conf)) {
      return Text::label(
        Text::t('Invalid string'),
        Text::ul($errors));
    }

    return Text::s(var_export($conf, TRUE));
  }

}
