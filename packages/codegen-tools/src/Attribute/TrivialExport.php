<?php

declare(strict_types = 1);

namespace Ock\CodegenTools\Attribute;

use Ock\CodegenTools\Util\CodeGen;
use Ock\CodegenTools\ValueExporterInterface;

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
