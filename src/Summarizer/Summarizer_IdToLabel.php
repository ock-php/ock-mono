<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Summarizer;

use Donquixote\ObCK\Formula\IdToLabel\Formula_IdToLabelInterface;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;
use Donquixote\ObCK\Util\ConfUtil;

/**
 * @STA
 */
class Summarizer_IdToLabel implements SummarizerInterface {

  /**
   * @var \Donquixote\ObCK\Formula\IdToLabel\Formula_IdToLabelInterface
   */
  private Formula_IdToLabelInterface $formula;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Formula\IdToLabel\Formula_IdToLabelInterface $formula
   */
  public function __construct(Formula_IdToLabelInterface $formula) {
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
      return Text::t('Unknown id "@id".')
        ->replaceS('@id', $id);
    }

    return $this->formula->idGetLabel($id)
      ?? Text::t('Unnamed id "@id".')
        ->replaceS('@id', $id);
  }
}
