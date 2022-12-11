<?php

declare(strict_types = 1);

namespace Drupal\ock\EventSubscriber;

use Drupal\ock\Attribute\Event\OnEvent;

trait EventSubscriberTrait {

  public static function getSubscribedEvents(): array {
    $rc = new \ReflectionClass(static::class);
    $events = [];
    foreach ($rc->getMethods() as $rm) {
      if ($rm->isAbstract() || !$rm->isPublic()) {
        continue;
      }
      if ($rm->getDeclaringClass()->getName() !== static::class) {
        continue;
      }
      foreach ($rc->getAttributes(
        OnEvent::class,
        \ReflectionAttribute::IS_INSTANCEOF,
      ) as $attribute) {
        /** @var \Drupal\ock\Attribute\Event\OnEvent $instance */
        $instance = $attribute->newInstance();
        $priority = $instance->getPriority();
        $events[$instance->getEventName()][] = ($priority !== NULL)
          ? [$rm->getName(), $priority]
          : $rm->getName();
      }
    }
    return $events;
  }

}
