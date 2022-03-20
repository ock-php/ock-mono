<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;
use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Iface\Formula_Iface;
use Donquixote\Ock\Formula\ValueFactory\Formula_ValueFactoryInterface;
use Donquixote\Ock\Formula\ValueProvider\Formula_ValueProvider_FixedPhp;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\ParamToLabel\ParamToLabelInterface;
use Donquixote\Ock\Text\Text;

#[OckIncarnator]
class IncarnatorPartial_ValueFactory extends IncarnatorPartial_FormulaReplacerBase {

  /**
   * @var \Donquixote\Ock\ParamToLabel\ParamToLabelInterface
   */
  private $paramToLabel;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\ParamToLabel\ParamToLabelInterface $paramToLabel
   */
  public function __construct(ParamToLabelInterface $paramToLabel) {
    parent::__construct(Formula_ValueFactoryInterface::class);
    $this->paramToLabel = $paramToLabel;
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaGetReplacement(FormulaInterface $formula, IncarnatorInterface $incarnator): ?FormulaInterface {

    /** @var \Donquixote\Ock\Formula\ValueFactory\Formula_ValueFactoryInterface $formula */

    try {
      $factory = $formula->getValueFactory();
    }
    catch (\Exception $e) {
      throw new IncarnatorException($e->getMessage(), 0, $e);
    }

    $params = $factory->getReflectionParameters();

    if ([] === $params) {
      return Formula_ValueProvider_FixedPhp::fromCallback($factory);
    }

    $builder = Formula::group();
    foreach ($params as $param) {
      $name = $param->getName();
      $param_formula = $this->paramGetFormula($param);
      if (!$param_formula) {
        throw new IncarnatorException(
          sprintf(
            'Cannot build formula for parameter $%s of %s.',
            $param->getName(),
            $factory->argsPhpGetPhp(['...'], new CodegenHelper())));
      }

      $param_label = $this->paramToLabel->paramGetLabel($param);

      $builder->add(
        $name,
        $param_formula,
        $param_label ?? Text::s('$' . $name));
    }

    return $builder->createWithCallback($factory);
  }

  /**
   * @param \ReflectionParameter $param
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   */
  private function paramGetFormula(\ReflectionParameter $param): ?FormulaInterface {

    $rtype = $param->getType();
    if (!$rtype instanceof \ReflectionNamedType || $rtype->isBuiltin()) {
      return NULL;
    }

    $formula = new Formula_Iface($rtype->getName(), $param->allowsNull());

    if (!$param->isOptional()) {
      return $formula;
    }

    try {
      $default = $param->getDefaultValue();
    }
    catch (\ReflectionException $e) {
      // This should be unreachable code.
      // Convert exception to unchecked.
      throw new \RuntimeException($e->getMessage(), 0, $e);
    }

    if ($default !== NULL) {
      // Unsupported default value.
      // @todo Log this.
      return NULL;
    }

    return $formula;
  }

}
