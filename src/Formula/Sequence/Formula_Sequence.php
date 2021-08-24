<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Sequence;

use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;
use Donquixote\ObCK\Translator\TranslatorInterface;

class Formula_Sequence extends Formula_SequenceBase {

  /**
   * {@inheritdoc}
   */
  public function deltaGetItemLabel(?int $delta): TextInterface {

    return (NULL === $delta)
      ? Text::t('New item')
      : Text::t('Item !n', [
        '!n' => Text::s('#' . $delta),
      ]);
  }
}
