<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityDisplay;

use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Donquixote\Cf\Schema\Select\CfSchema_Select_TwoStepFlatSelectComposite;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Layout\LayoutInterface;
use Drupal\renderkit8\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface;
use Drupal\renderkit8\Schema\CfSchema_ViewIdWithDisplayId;
use Drupal\views\Views;

/**
 * Show a view (from "views" module) for the entity.
 */
class EntityDisplay_Layout extends EntityDisplayBase {

  /**
   * @var \Drupal\Core\Layout\LayoutInterface
   */
  private $layout;

  /**
   * @var \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface[]
   */
  private $regionDisplays;

  /**
   * @return \Drupal\renderkit8\EntityDisplay\CfSchema_EntityDisplay_Layout
   */
  public static function schema() {
    return new CfSchema_EntityDisplay_Layout();
  }

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Layout\LayoutInterface $layout
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface[] $region_displays
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
   * @inheritDoc
   */
  public function buildEntity(EntityInterface $entity) {

    $elements = [];
    foreach ($this->regionDisplays as $region_name => $display) {
      $elements[$region_name] = $display->buildEntity($entity);
    }

    return $this->layout->build($elements);
  }

}
