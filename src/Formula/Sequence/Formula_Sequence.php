<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Sequence;

use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\Translator\TranslatorInterface;

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
