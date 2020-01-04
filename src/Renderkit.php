<?php
declare(strict_types=1);

namespace Drupal\renderkit8;

use Drupal\renderkit8\BuildProcessor\BuildProcessor_Container;
use Drupal\renderkit8\EntityBuildProcessor\EntityBuildProcessor_Wrapper_ContextualLinks;
use Drupal\renderkit8\EntityDisplay\Decorator\EntityDisplay_WithBuildProcessor;
use Drupal\renderkit8\EntityDisplay\Decorator\EntityDisplay_WithEntityBuildProcessor;
use Drupal\renderkit8\EntityDisplay\EntityDisplayInterface;

/**
 * Class with static methods for easier construction of display handlers.
 */
class Renderkit {

  /**
   * Wraps the build from the decorated display handler into a container.
   *
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface $decorated
   * @param string $tagName
   *
   * @return \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface
   */
  public static function entityContainer(EntityDisplayInterface $decorated, $tagName = 'div') {
    return EntityDisplay_WithBuildProcessor::create(
      $decorated,
      BuildProcessor_Container::create($tagName));
  }

  /**
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface $decorated
   * @param string $tagName
   *
   * @return \Drupal\renderkit8\EntityDisplay\Decorator\EntityDisplay_WithEntityBuildProcessor
   */
  public static function entityContextualLinksWrapper(EntityDisplayInterface $decorated, $tagName = 'article') {
    $processor = (new EntityBuildProcessor_Wrapper_ContextualLinks)->setTagName($tagName);
    return new EntityDisplay_WithEntityBuildProcessor($decorated, $processor);
  }

}
