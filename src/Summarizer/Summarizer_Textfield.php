<?php
declare(strict_types=1);

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface;
use Donquixote\Cf\Text\Text;
use Donquixote\Cf\Util\HtmlUtil;

/**
 * @STA
 */
class Summarizer_Textfield implements SummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface $schema
   */
  public function __construct(CfSchema_TextfieldInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?\Donquixote\Cf\Text\TextInterface {

    if (!\is_string($conf)) {
      return Text::t('Value is not a string.');
    }

    if ([] !== $errors = $this->schema->textGetValidationErrors($conf)) {
      return 'Invalid string: ' . HtmlUtil::sanitize(reset($errors));
    }

    return HtmlUtil::sanitize(var_export($conf, TRUE));
  }
}
