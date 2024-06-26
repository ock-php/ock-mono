<?php
declare(strict_types=1);

namespace Drupal\renderkit\ListFormat;

use Drupal\renderkit\Formula\Formula_ListSeparator;
use Ock\Ock\Attribute\Parameter\OckFormulaFromClass;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * Concatenates the list items with a separator.
 */
#[OckPluginInstance('separator', 'Separator')]
class ListFormat_Separator implements ListFormatInterface {

  /**
   * @param string $separator
   */
  public function __construct(
    #[OckOption('separator', 'Separator')]
    #[OckFormulaFromClass(Formula_ListSeparator::class)]
    private readonly string $separator = '',
  ) {}

  /**
   * {@inheritdoc}
   */
  public function buildList(array $builds): array {
    return [
      /* @see renderkit_theme() */
      /* @see theme_themekit_separator_list() */
      '#theme' => 'themekit_separator_list',
      '#separator' => $this->separator,
      '#items' => $builds,
    ];
  }

}
