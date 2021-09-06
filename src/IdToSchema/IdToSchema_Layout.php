<?php
declare(strict_types=1);

namespace Drupal\renderkit\IdToSchema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Layout\LayoutPluginManagerInterface;

class IdToSchema_Layout implements IdToSchemaInterface {

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
  public function idGetSchema($id): ?CfSchemaInterface {
    try {
      $plugin = $this->manager->createInstance($id);
    }
    catch (PluginException $e) {
      // @todo Log this?
      return NULL;
    }
    return new CfSchema_Drupal
  }

}
