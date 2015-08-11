<?php

namespace Drupal\renderkit;

use Drupal\renderkit\BuildProcessor\ContainerBuildProcessor;
use Drupal\renderkit\EntityBuildProcessor\Wrapper\EntityContextualLinksWrapper;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

/**
 * Class with static methods for easier construction of display handlers.
 */
class Renderkit {

  /**
   * Wraps the build from the decorated display handler into a container.
   *
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $decorated
   * @param string $tagName
   *
   * @return \Drupal\renderkit\BuildProcessor\ContainerBuildProcessor
   */
  static function entityContainer(EntityDisplayInterface $decorated, $tagName = 'div') {
    return (new ContainerBuildProcessor())
      ->setTagName($tagName)
      ->decorate($decorated);
  }

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $decorated
   * @param string $tagName
   *
   * @return \Drupal\renderkit\EntityBuildProcessor\Wrapper\EntityContextualLinksWrapper
   */
  static function entityContextualLinksWrapper(EntityDisplayInterface $decorated, $tagName = 'article') {
    return (new EntityContextualLinksWrapper())
      ->setTagName($tagName)
      ->decorate($decorated);
  }

}
