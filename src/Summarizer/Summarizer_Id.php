<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Formula\Id\Formula_IdInterface;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\Util\ConfUtil;

/**
 * @STA
 */
class Summarizer_Id implements SummarizerInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Id\Formula_IdInterface
   */
  private $formula;

  /**
   * @param \Donquixote\OCUI\Formula\Id\Formula_IdInterface $formula
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
