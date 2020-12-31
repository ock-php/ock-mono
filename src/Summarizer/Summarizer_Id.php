<?php
declare(strict_types=1);

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Schema\Id\CfSchema_IdInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\ConfUtil;

/**
 * @STA
 */
class Summarizer_Id implements SummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Id\CfSchema_IdInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Translator\TranslatorInterface
   */
  private $translator;

  /**
   * @param \Donquixote\Cf\Schema\Id\CfSchema_IdInterface $schema
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   */
  public function __construct(CfSchema_IdInterface $schema, TranslatorInterface $translator) {
    $this->schema = $schema;
    $this->translator = $translator;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?\Donquixote\Cf\Text\TextInterface {

    if (NULL === $id = ConfUtil::confGetId($conf)) {
      return $this->translator->translate('Required id missing.');
    }

    if (!$this->schema->idIsKnown($id)) {
      return $this->translator->translate(
        'Unknown id "@id" for options schema.',
        ['@id' => $id]);
    }

    return $this->schema->idGetLabel($id);
  }
}
