<?php

declare(strict_types = 1);

namespace Donquixote\CodegenTools\Attribute;

use Donquixote\CodegenTools\ValueExporterInterface;

interface ExportableAttributeInterface {

  /**
   * @param object $object
   *
   * @return string
   *   Php expression.
   *
   * @throws \Donquixote\CodegenTools\Exception\CodegenException
   */
  public function export(object $object, ValueExporterInterface $exporter, bool $enclose): string;

}
