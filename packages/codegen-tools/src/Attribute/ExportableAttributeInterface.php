<?php

declare(strict_types = 1);

namespace Ock\CodegenTools\Attribute;

use Ock\CodegenTools\ValueExporterInterface;

interface ExportableAttributeInterface {

  /**
   * @param object $object
   * @param \Ock\CodegenTools\ValueExporterInterface $exporter
   * @param bool $enclose
   *
   * @return string
   *   Php expression.
   *
   * @throws \Ock\CodegenTools\Exception\CodegenException
   */
  public function export(object $object, ValueExporterInterface $exporter, bool $enclose): string;

}
