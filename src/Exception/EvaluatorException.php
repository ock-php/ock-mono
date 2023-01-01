<?php

declare(strict_types=1);

namespace Donquixote\DID\Exception;

/**
 * Exception to be thrown in generated code and in evaluators.
 */
class EvaluatorException extends \Exception {

  public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = NULL) {
    parent::__construct($message, $code, $previous);
  }

}
