<?php
declare(strict_types=1);

namespace Drupal\renderkit\LabeledListFormat;

use Drupal\renderkit\ListFormat\ListFormatInterface;
use Ock\Ock\Attribute\Parameter\OckAdaptee;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * @todo Mark as adapter/inline.
 */
#[OckPluginInstance('listformat', 'List format')]
class LabeledListFormat_ListFormat implements LabeledListFormatInterface {

  /**
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface $listFormat
   */
  public function __construct(
    #[OckAdaptee]
    private readonly ListFormatInterface $listFormat,
  ) {}

  /**
   * @param array[] $builds
   *   Render arrays, e.g. for field items or field group children.
   * @param string $label
   *   A label, e.g. for a field or field group.
   *
   * @return array
   *   Combined render array.
   */
  public function build(array $builds, string $label): array {
    return $this->listFormat->buildList($builds);
  }

}
