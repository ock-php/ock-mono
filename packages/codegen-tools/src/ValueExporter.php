<?php

declare(strict_types = 1);

namespace Ock\CodegenTools;

use Ock\CodegenTools\Attribute\ExportableAttributeInterface;
use Ock\CodegenTools\Exception\CodegenException;
use Ock\CodegenTools\Util\CodeGen;

class ValueExporter implements ValueExporterInterface {

  /**
   * {@inheritdoc}
   */
  public function export(mixed $value, bool $enclose = false): string {
    if (\is_array($value)) {
      return $this->exportArray($value);
    }
    if (\is_object($value)) {
      return $this->exportObject($value, $enclose);
    }
    return var_export($value, TRUE);
  }

  /**
   * @param array $array
   *
   * @return string
   *
   * @throws \Ock\CodegenTools\Exception\CodegenException
   */
  private function exportArray(array $array): string {
    $valuesPhp = [];
    foreach ($array as $k => $v) {
      $valuesPhp[$k] = $this->export($v);
    }
    return CodeGen::phpArray($valuesPhp);
  }

  /**
   * @param object $object
   * @param bool $enclose
   *
   * @return string
   *
   * @throws \Ock\CodegenTools\Exception\CodegenException
   */
  private function exportObject(object $object, bool $enclose): string {
    if ($object instanceof \stdClass) {
      return '(object) ' . $this->exportArray((array) $object);
    }

    $class = get_class($object);

    $constructorArgsPhp = $this->extractConstructorArgsPhp($object);

    if ($constructorArgsPhp !== null) {
      return CodeGen::phpConstruct($class, $constructorArgsPhp, $enclose);
    }

    $rc = new \ReflectionClass($object);

    if ($rc->isAnonymous()) {
      throw new CodegenException('Cannot export instance of anonymous class.');
    }

    foreach ($rc->getAttributes(ExportableAttributeInterface::class, \ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
      /** @var ExportableAttributeInterface $instance */
      $instance = $attribute->newInstance();
      return $instance->export($object, $this, $enclose);
    }

    // Attempt to serialize.
    // If the class does not support it, an exception will be thrown.
    try {
      return \sprintf(
        '\\unserialize(%s)',
        var_export(\serialize($object), TRUE),
      );
    }
    catch (\Throwable $e) {
      throw new CodegenException('Cannot serialize the given object.', 0, $e);
    }
  }

  /**
   * @param object $object
   *
   * @return string[]|null
   *
   * @throws \Ock\CodegenTools\Exception\CodegenException
   */
  private function extractConstructorArgsPhp(object $object): ?array {
    /** @noinspection PhpVoidFunctionResultUsedInspection */
    return match (get_class($object)) {
      // Support some known classes.
      // @todo Make this pluggable.
      \Closure::class => $this->fail('Cannot export closure.'),
      \ReflectionClass::class => $object->isAnonymous()
        ? $this->fail('Cannot export ReflectionClass for anonymous class.')
        : ['\\' . $object->name . '::class'],
      \ReflectionFunction::class => $object->isClosure()
        ? $this->fail('Cannot export ReflectionFunction for closure.')
        : [var_export($object->name, TRUE)],
      \ReflectionMethod::class => [
        $this->extractConstructorArgsPhp($object->getDeclaringClass())[0],
        $object->isClosure()
          ? $this->fail('Cannot export ReflectionMethod for closure.')
          : var_export($object->name, TRUE),
      ],
      \ReflectionProperty::class,
      \ReflectionClassConstant::class => [
        $this->extractConstructorArgsPhp($object->getDeclaringClass())[0],
        var_export($object->name, TRUE),
      ],
      \ReflectionAttribute::class => $this->fail('Cannot export reflection attribute.'),
      \ReflectionParameter::class => [
        match (get_class($rf = $object->getDeclaringFunction())) {
          \ReflectionMethod::class => CodeGen::phpArray($this->extractConstructorArgsPhp($rf)),
          \ReflectionFunction::class => $this->extractConstructorArgsPhp($rf)[0],
        },
        $object->getPosition(),
      ],
      default => NULL,
    };
  }

  /**
   * @param string $message
   *
   * @return never
   *
   * @throws \Ock\CodegenTools\Exception\CodegenException
   */
  private function fail(string $message): never {
    throw new CodegenException($message);
  }

}
