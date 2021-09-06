<?php
declare(strict_types=1);

namespace Drupal\renderkit\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;
use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Layout\LayoutPluginManagerInterface;

class IdToFormula_Layout implements IdToFormulaInterface {

  /**
   * @var \Drupal\Core\Layout\LayoutPluginManagerInterface
   */
  private $manager;

  /**
   * @param \Drupal\Core\Layout\LayoutPluginManagerInterface $manager
   */
  public function __construct(LayoutPluginManagerInterface $manager) {
    $this->manager = $manager;
  }

  /**
   * @inheritDoc
   */
  public function idGetFormula($id): ?FormulaInterface {
    try {
      $plugin = $this->manager->createInstance($id);
    }
    catch (PluginException $e) {
      // @todo Log this?
      return NULL;
    }
    return new Formula_Drupal
  }

}
