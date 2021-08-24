<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\DefinitionToFormula\Helper;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;

/**
 * @internal
 *
 * These are helper objects used within
 * @see \Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormula_Mappers,
 *
 */
interface DefinitionToFormulaHelperInterface {

  /**
   * @param object $object
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\ObCK\Exception\FormulaCreationException
   */
  public function objectGetFormula(object $object): FormulaInterface;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $factory
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\ObCK\Exception\FormulaCreationException
   */
  public function factoryGetFormula(CallbackReflectionInterface $factory, CfContextInterface $context = NULL): FormulaInterface;

}
