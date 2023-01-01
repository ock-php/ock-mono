<?php

declare(strict_types = 1);

namespace Donquixote\DID\Exception;

/**
 * A declaration, typically one with attributes, is malformed.
 */
class DiscoveryException extends \Exception {

  public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null) {
    parent::__construct($message, $code, $previous);
  }

}
