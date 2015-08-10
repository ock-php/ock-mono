<?php

namespace Drupal\renderkit\Exception;

/**
 * Exception that indicates a recursion in entity display execution.
 *
 * @see \Drupal\renderkit\EntityDisplay\Decorator\EntityDisplayRecursionDetectionDecorator
 */
class EntityDisplayRecursionException extends \RuntimeException {

}
