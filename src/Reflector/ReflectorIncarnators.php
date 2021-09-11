<?php

declare(strict_types=1);

namespace Drupal\cu\Reflector;

use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;
use Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface;
use Donquixote\ObCK\Formula\ValueFactory\Formula_ValueFactoryInterface;

class ReflectorIncarnators {

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface $formula
   *
   * @return \Reflector
   *
   * @throws \ReflectionException
   */
  public function fromIface(Formula_IfaceInterface $formula): \Reflector {
    return new \ReflectionClass($formula->getInterface());
  }

  public function fromValueFactory(Formula_ValueFactoryInterface $formula) {
    $factory = $formula->getValueFactory();

    $helper = new CodegenHelper();
    $php = $factory->argsPhpGetPhp(['...'], $helper);

    if (preg_match('@^new ((?:\\\\\w+)+)\(@', $php, $m)) {
      return new \ReflectionClass($m[1]);
    }

    if (preg_match('@^((?:\\\\\w+)+)::(\w+)\(@', $php, $m)) {
      return new \ReflectionMethod($m[1], $m[2]);
    }

    return NULL;
  }

}
