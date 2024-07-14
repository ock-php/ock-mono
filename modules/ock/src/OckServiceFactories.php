<?php

declare(strict_types=1);

namespace Drupal\ock;

use Drupal\Core\Extension\ModuleExtensionList;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\DID\Attribute\Service;
use Ock\Ock\Plugin\GroupLabels\PluginGroupLabels;
use Ock\Ock\Plugin\GroupLabels\PluginGroupLabelsInterface;
use Ock\Ock\Text\Text;

/**
 * Some service factories that don't have their own class.
 *
 * @see \Drupal\ock\OckServiceProvider
 */
class OckServiceFactories {

  #[Service]
  public static function pluginGroupLabels(
    #[GetService('extension.list.module')] ModuleExtensionList $modules,
  ): PluginGroupLabelsInterface {
    $labels = [];
    foreach ($modules->getList() as $module => $info) {
      $labels[$module] = Text::s($info->getName());
    }
    return new PluginGroupLabels($labels);
  }

}
