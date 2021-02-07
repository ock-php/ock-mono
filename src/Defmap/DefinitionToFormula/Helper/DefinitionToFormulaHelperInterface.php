<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionToFormula\Helper;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;

/**
 * @internal
 *
 * These are helper objects used within
 * @see \Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormula_Mappers,
 *
 */
interface DefinitionToFormulaHelperInterface {

  /**
   * @param object $object
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\OCUI\Exception\FormulaCreationException
   */
  public function objectGetFormula(object $object): FormulaInterface;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $factory
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\OCUI\Exception\FormulaCreationException
   */
  public function factoryGetFormula(CallbackReflectionInterface $factory, CfContextInterface $context = NULL): FormulaInterface;

}
