<?php

namespace Donquixote\Ock\Formula\Contextual;

use Donquixote\Ock\Context\CfContextInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\FixedConf\Formula_FixedConf;
use Donquixote\Ock\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface;

class Formula_Contextual_ValueForKey implements Formula_ContextualInterface {

  public function __construct(private readonly string $key) {}

  public function getDecorated(CfContextInterface $context = null): FormulaInterface {
    $value = $context->paramNameGetValue($this->key);
    return new Formula_FixedConf(NULL, $value);
  }

}
