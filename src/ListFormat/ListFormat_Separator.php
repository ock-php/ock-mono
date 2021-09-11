<?php
declare(strict_types=1);

namespace Drupal\renderkit\ListFormat;

use Donquixote\Ock\Formula\GroupVal\Formula_GroupVal_Callback;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface;
use Drupal\renderkit\Formula\Formula_ListSeparator;

/**
 * Concatenates the list items with a separator.
 */
class ListFormat_Separator implements ListFormatInterface {

  /**
   * @var string
   */
  private $separator;

  /**
   * @CfrPlugin(
   *   id = "separator",
   *   label = @t("Separator")
   * )
   *
   * @return \Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface
   */
  public static function createFormula(): Formula_GroupValInterface {

    return Formula_GroupVal_Callback::fromClass(
      __CLASS__,
      [
        new Formula_ListSeparator(),
      ],
      [
        t('Separator'),
      ]);
  }

  /**
   * @param string $separator
   */
  public function __construct($separator = '') {
    $this->separator = $separator;
  }

  /**
   * @param array[] $builds
   *   Array of render arrays for list items.
   *   Must not contain any property keys like "#..".
   *
   * @return array
   *   Render array for the list.
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
