<?php

declare(strict_types = 1);

namespace Donquixote\CodegenTools\Attribute;

use Donquixote\CodegenTools\Util\CodeGen;
use Donquixote\CodegenTools\ValueExporterInterface;

#[\Attribute(\Attribute::TARGET_CLASS)]
class TrivialExport implements ExportableAttributeInterface {

  /**
   * {@inheritdoc}
   */
  public function export(object $object, ValueExporterInterface $exporter, bool $enclose): string {
    $rc = new \ReflectionClass($object);
    $argsPhp = [];
    foreach ($rc->getConstructor()?->getParameters() as $parameter) {
      $argsPhp[] = $exporter->export($object->{$parameter->name}, $enclose);
    }
    return CodeGen::phpConstruct($rc->name, $argsPhp);
  }

}
