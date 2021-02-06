<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Schema\Textfield\CfSchema_TextfieldInterface;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;

/**
 * @STA
 */
class Summarizer_Textfield implements SummarizerInterface {

  /**
   * @var \Donquixote\OCUI\Schema\Textfield\CfSchema_TextfieldInterface
   */
  private $schema;

  /**
   * @param \Donquixote\OCUI\Schema\Textfield\CfSchema_TextfieldInterface $schema
   */
  public function __construct(CfSchema_TextfieldInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    if (!\is_string($conf)) {
      return Text::t('Value is not a string.');
    }

    if ([] !== $errors = $this->schema->textGetValidationErrors($conf)) {
      return Text::label(
        Text::t('Invalid string'),
        Text::ul($errors));
    }

    return Text::s(var_export($conf, TRUE));
  }
}
