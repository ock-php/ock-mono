<?php

declare(strict_types = 1);

namespace Donquixote\DID\Attribute\Exportable;

use Donquixote\DID\CodegenTools\ValueExporterInterface;

interface ExportableAttributeInterface {

  /**
   * @param object $object
   *
   * @return string
   *   Php expression.
   */
  public function export(object $object, ValueExporterInterface $exporter): string;

}
