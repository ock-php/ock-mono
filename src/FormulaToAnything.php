<?php

declare(strict_types=1);

namespace Drupal\cu;

use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Drupal\cu\Util\UtilBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FormulaToAnything extends UtilBase {

  public static function fromContainer(ContainerInterface $container = NULL): FormulaToAnythingInterface {
    if (!$container) {
      $container = \Drupal::getContainer();
    }
    $instance = $container->get(FormulaToAnythingInterface::class);
    if (!$instance) {
      throw new \RuntimeException('Service not found.');
    }
    if (!$instance instanceof FormulaToAnythingInterface) {
      throw new \RuntimeException('Service has unexpected type.');
    }
    return $instance;
  }

}
