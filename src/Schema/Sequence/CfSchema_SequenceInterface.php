<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Sequence;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Translator\TranslatorInterface;

interface CfSchema_SequenceInterface extends CfSchemaInterface {

  /**
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  public function getItemSchema(): CfSchemaInterface;

  /**
   * Gets a label for the nth sequence item.
   *
   * @param int|null $delta
   *   Index of the sequence item, or NULL for the "new item" item.
   * @param \Donquixote\OCUI\Translator\TranslatorInterface $helper
   *
   * @return string
   */
  public function deltaGetItemLabel(?int $delta, TranslatorInterface $helper): string;

}
