<?php
declare(strict_types=1);

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Schema\Id\CfSchema_IdInterface;
use Donquixote\Cf\Text\Text;
use Donquixote\Cf\Text\TextInterface;
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
   * @param \Donquixote\Cf\Schema\Id\CfSchema_IdInterface $schema
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
