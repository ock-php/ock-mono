<?php

namespace Drupal\renderkit8\Exception;

/**
 * Exception that indicates a recursion in entity display execution.
 *
 * @see \Drupal\renderkit8\EntityDisplay\Decorator\EntityDisplayRecursionDetectionDecorator
 */
class EntityDisplayRecursionException extends \RuntimeException {

}
