<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Sequence;

use Donquixote\OCUI\Translator\TranslatorInterface;

class CfSchema_Sequence extends CfSchema_SequenceBase {

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
