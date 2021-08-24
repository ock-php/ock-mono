<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Summarizer;

use Donquixote\ObCK\Formula\Id\Formula_IdInterface;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;
use Donquixote\ObCK\Util\ConfUtil;

/**
 * @STA
 */
class Summarizer_Id implements SummarizerInterface {

  /**
   * @var \Donquixote\ObCK\Formula\Id\Formula_IdInterface
   */
  private $formula;

  /**
   * @param \Donquixote\ObCK\Formula\Id\Formula_IdInterface $formula
   */
  public function __construct(Formula_IdInterface $formula) {
    $this->formula = $formula;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    if (NULL === $id = ConfUtil::confGetId($conf)) {
      return Text::t('Required id missing.');
    }

    if (!$this->formula->idIsKnown($id)) {
      return Text::t(
        'Unknown id "@id".',
        ['@id' => $id]);
    }

    return $this->formula->idGetLabel($id);
  }
}
