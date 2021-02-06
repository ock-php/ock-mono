<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Schema\Id\CfSchema_IdInterface;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\Util\ConfUtil;

/**
 * @STA
 */
class Summarizer_Id implements SummarizerInterface {

  /**
   * @var \Donquixote\OCUI\Schema\Id\CfSchema_IdInterface
   */
  private $schema;

  /**
   * @param \Donquixote\OCUI\Schema\Id\CfSchema_IdInterface $schema
   */
  public function __construct(CfSchema_IdInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    if (NULL === $id = ConfUtil::confGetId($conf)) {
      return Text::t('Required id missing.');
    }

    if (!$this->schema->idIsKnown($id)) {
      return Text::t(
        'Unknown id "@id".',
        ['@id' => $id]);
    }

    return $this->schema->idGetLabel($id);
  }
}
