<?php
declare(strict_types=1);

namespace Drupal\renderkit\ListFormat;

use Drupal\renderkit\Formula\Formula_ListFormat_Expert;
use Ock\Ock\Attribute\Plugin\OckPluginFormula;
use Ock\Ock\Core\Formula\FormulaInterface;

class ListFormat_ElementDefaults implements ListFormatInterface {

  /**
   * @return \Ock\Ock\Core\Formula\FormulaInterface
   */
  #[OckPluginFormula(self::class, 'expert', 'Expert')]
  public static function createExpertFormula(): FormulaInterface {
    return new Formula_ListFormat_Expert();
  }

  /**
   * @param array $elementDefaults
   */
  public function __construct(
    private readonly array $elementDefaults,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function buildList(array $builds): array {
    return $builds + $this->elementDefaults;
  }
}
