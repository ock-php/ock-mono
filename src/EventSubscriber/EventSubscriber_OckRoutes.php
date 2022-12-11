<?php

declare(strict_types = 1);

namespace Drupal\ock\EventSubscriber;

use Drupal\Core\Routing\RouteBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Route;

class EventSubscriber_OckRoutes implements EventSubscriberInterface {

  use EventSubscriberTrait;

  public function onRoutes(RouteBuildEvent $event) {
    /** @var $route Route */
    foreach ($event->getRouteCollection() as $name => $route) {
      if ($route->hasOption('type')
        && $route->getOption('type') === 'annotation'
      ) {
        $routeCollection = $this->annotationDirectoryLoader->load($this->rootPath . $this->getRoutePath($route));
        $routeCollection->addPrefix($route->getPath());

        $event->getRouteCollection()->addCollection($routeCollection);
        $event->getRouteCollection()->remove($name);
      }
    }
  }

}
