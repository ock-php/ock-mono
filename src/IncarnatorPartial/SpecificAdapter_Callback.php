<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

class SpecificAdapter_Callback extends SpecificAdapterBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   *
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string|null $formulaType
   * @param string|null $resultType
   */
  public function __construct(
    CallbackReflectionInterface $callback,
    $formulaType = NULL,
    $resultType = NULL
  ) {
    $this->callback = $callback;
    parent::__construct($formulaType, $resultType);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaDoGetObject(
    FormulaInterface $formula,
    string $interface,
    UniversalAdapterInterface $universalAdapter
  ): ?object {

    try {
      // Other arguments, e.g. services, might already be part of the callback.
      return $this->callback->invokeArgs([$formula, $universalAdapter]);
    }
    catch (\Exception $e) {
      throw new IncarnatorException($e->getMessage(), 0, $e);
    }
  }

}
