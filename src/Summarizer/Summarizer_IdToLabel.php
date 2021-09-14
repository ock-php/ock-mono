<?php

declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Ock\Formula\IdToLabel\Formula_IdToLabelInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\Util\ConfUtil;

/**
 * @STA
 */
class Summarizer_IdToLabel implements SummarizerInterface {

  /**
   * @var \Donquixote\Ock\Formula\IdToLabel\Formula_IdToLabelInterface
   */
  private Formula_IdToLabelInterface $formula;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\IdToLabel\Formula_IdToLabelInterface $formula
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
      return Text::s($id)
        ->wrapT('@id', 'Unknown id "@id".');
    }

    return $this->formula->idGetLabel($id)
      ?? Text::s($id)
        ->wrapT('@id', 'Unnamed id "@id"');
  }

}
