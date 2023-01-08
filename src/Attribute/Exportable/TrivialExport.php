<?php

declare(strict_types = 1);

namespace Donquixote\DID\Attribute\Exportable;

use Donquixote\DID\CodegenTools\ValueExporterInterface;
use Donquixote\DID\Util\PhpUtil;

#[\Attribute(\Attribute::TARGET_CLASS)]
class TrivialExport implements ExportableAttributeInterface {

  /**
   * {@inheritdoc}
   */
  public function export(object $object, ValueExporterInterface $exporter): string {
    $rc = new \ReflectionClass($object);
    $argsPhp = [];
    foreach ($rc->getConstructor()?->getParameters() as $parameter) {
      $argsPhp[] = $exporter->export($object->{$parameter->name});
    }
    return PhpUtil::phpConstruct($rc->name, $argsPhp);
  }

}
