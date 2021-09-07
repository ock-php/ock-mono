<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Layout\LayoutInterface;
use Drupal\Core\Layout\LayoutPluginManagerInterface;
use Drupal\renderkit\IdToFormula\IdToFormula_LayoutAndRegions;

/**
 * Show a view (from "views" module) for the entity.
 */
class EntityDisplay_Layout extends EntityDisplayBase {

  /**
   * @var \Drupal\Core\Layout\LayoutInterface
   */
  private $layout;

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[]
   */
  private $regionDisplays;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Layout\LayoutInterface $layout
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[] $region_displays
   *
   * @throws \Exception
   *   Layouts do not match up.
   */
  public function __construct(LayoutInterface $layout, $region_displays) {
    $this->layout = $layout;
    $this->regionDisplays = $region_displays;
    $this->throwIfInvalid();
  }

  /**
   * @ObCK("layout", "Layout")
   *
   * @param \Drupal\Core\Layout\LayoutPluginManagerInterface $layoutManager
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function formula(LayoutPluginManagerInterface $layoutManager): FormulaInterface {
    return IdToFormula_LayoutAndRegions::formula(
      EntityDisplay::formula(),
      $layoutManager,
      [self::class, 'create']);
  }

  /**
   * @param \Drupal\Core\Layout\LayoutInterface $layout
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[][] $regions
   *   Format: $[$region_id][$delta] = $display.
   *
   * @return self
   * @throws \Exception
   *   Layouts do not match up.
   */
  public static function create(LayoutInterface $layout, array $regions): self {
    $region_displays = [];
    foreach ($regions as $delta => $displays) {
      $region_displays[$delta] = new EntityDisplay_Sequence($displays);
    }
    return new self($layout, $region_displays);
  }

  /**
   * Checks if the region displays match up with the layout regions.
   *
   * @throws \Exception
   */
  private function throwIfInvalid() {

    $names = $this->layout->getPluginDefinition()->getRegionNames();
    $names_map = array_fill_keys($names, TRUE);

    $orphan_regions = array_diff_key($names_map, $this->regionDisplays);
    // @todo Perhaps this is not needed? Empty regions can remain empty?
    if ([] !== $orphan_regions) {
      throw new \Exception(
        strtr(
          'Empty regions: @displays',
          ['@displays' => implode(', ', array_keys($orphan_regions))]));
    }

    $orphan_displays = array_diff_key($this->regionDisplays, $names_map);
    if ([] !== $orphan_displays) {
      throw new \Exception(
        strtr(
          'Unnecessary displays: @displays',
          ['@displays' => implode(', ', array_keys($orphan_displays))]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function buildEntity(EntityInterface $entity): array {

    $elements = [];
    foreach ($this->regionDisplays as $region_name => $display) {
      $elements[$region_name] = $display->buildEntity($entity);
    }

    return $this->layout->build($elements);
  }

}
