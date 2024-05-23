<?php
declare(strict_types=1);

namespace Drupal\renderkit;

use Drupal\renderkit\BuildProcessor\BuildProcessor_Container;
use Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessor_Wrapper_ContextualLinks;
use Drupal\renderkit\EntityDisplay\Decorator\EntityDisplay_WithBuildProcessor;
use Drupal\renderkit\EntityDisplay\Decorator\EntityDisplay_WithEntityBuildProcessor;
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
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  public static function entityContainer(EntityDisplayInterface $decorated, string $tagName = 'div'): EntityDisplayInterface {
    return EntityDisplay_WithBuildProcessor::create(
      $decorated,
      BuildProcessor_Container::create($tagName));
  }

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $decorated
   * @param string $tagName
   *
   * @return \Drupal\renderkit\EntityDisplay\Decorator\EntityDisplay_WithEntityBuildProcessor
   */
  public static function entityContextualLinksWrapper(EntityDisplayInterface $decorated, string $tagName = 'article'): EntityDisplay_WithEntityBuildProcessor {
    $processor = (new EntityBuildProcessor_Wrapper_ContextualLinks)->setTagName($tagName);
    return new EntityDisplay_WithEntityBuildProcessor($decorated, $processor);
  }

}
