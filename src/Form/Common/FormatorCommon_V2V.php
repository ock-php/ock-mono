<?php

declare(strict_types=1);

namespace Donquixote\Ock\Form\Common;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\AdapterTargetType;
use Donquixote\Adaptism\Attribute\Parameter\UniversalAdapter;
use Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\Ock\FormulaAdapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

class FormatorCommon_V2V {

  /**
   * @template T as \Donquixote\Ock\Form\Common\FormatorCommonInterface
   *
   * @param \Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface $formula
   * @param class-string<T> $interface
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return T|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function create(
    #[Adaptee] Formula_ValueToValueBaseInterface $formula,
    #[AdapterTargetType] string $interface,
    #[UniversalAdapter] UniversalAdapterInterface $universalAdapter,
  ): ?FormatorCommonInterface {

    if (!is_a($interface, FormatorCommonInterface::class, TRUE)) {
      return NULL;
    }

    /** @var class-string<T> $interface */
    return $universalAdapter->adapt($formula->getDecorated(), $interface);
  }

}
