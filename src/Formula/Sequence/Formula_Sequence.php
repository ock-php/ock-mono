<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Sequence;

use Donquixote\OCUI\Translator\TranslatorInterface;

class Formula_Sequence extends Formula_SequenceBase {

  /**
   * {@inheritdoc}
   */
  public function deltaGetItemLabel(?int $delta, TranslatorInterface $helper): string {

    return (NULL === $delta)
      ? $helper->translate('New item')
      : $helper->translate(
        'Item !n',
        ['!n' => '#' . $delta]);
  }
}
