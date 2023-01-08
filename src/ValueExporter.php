<?php

declare(strict_types = 1);

namespace Donquixote\CodegenTools;

use Donquixote\CodegenTools\Attribute\ExportableAttributeInterface;
use Donquixote\CodegenTools\Exception\CodegenException;
use Donquixote\CodegenTools\Util\CodeGen;

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
   * @param bool $enclose
   *
   * @return string
   *
   * @throws \Donquixote\CodegenTools\Exception\CodegenException
   */
  private function exportArray(array $array): string {
    $valuesPhp = [];
    foreach ($array as $k => $v) {
      $valuesPhp[$k] = $this->export($v, false);
    }
    return CodeGen::phpArray($valuesPhp);
  }

  /**
   * @param object $object
   * @param bool $enclose
   *
   * @return string
   *
   * @throws \Donquixote\CodegenTools\Exception\CodegenException
   */
  private function exportObject(object $object, bool $enclose): string {
    if ($object instanceof \stdClass) {
      return '(object) ' . $this->exportArray((array) $object);
    }

    $class = get_class($object);

    /** @noinspection PhpVoidFunctionResultUsedInspection */
    // @todo Use the $class variable once PhpStorm fixes static analysis. See
    //   https://youtrack.jetbrains.com/issue/WI-70695/Type-recognition-with-getclass-vs-getdebugtype-in-match
    $php = match (get_class($object)) {
      // Support some known classes.
      // @todo Make this pluggable.
      \Closure::class => $this->fail('Cannot export closure.'),
      \ReflectionClass::class => $object->isAnonymous()
        ? $this->fail('Cannot export ReflectionClass for anonymous class.')
        : $this->construct($class, [$object->name]),
      \ReflectionFunction::class => $object->isClosure()
        ? $this->fail('Cannot export ReflectionFunction for closure.')
        : $this->construct($class, [$object->name]),
      \ReflectionMethod::class,
      \ReflectionProperty::class,
      \ReflectionClassConstant::class => $object->getDeclaringClass()->isAnonymous()
        ? $this->fail(sprintf(
          'Cannot export %s for anonymous class.',
          get_class($object),
        ))
        : $this->construct($class, [$object->class, $object->name]),
      \ReflectionAttribute::class => $this->fail('Cannot export reflection attribute.'),
      \ReflectionParameter::class => $this->export($object->getDeclaringFunction(), true)
        . '->getParameter(' . var_export($object->name, TRUE) . ')',
      default => NULL,
    };

    if ($php !== null) {
      return $php;
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
    catch (\Exception $e) {
      throw new CodegenException('Cannot serialize the given object.', 0, $e);
    }
  }

  /**
   * @param class-string $class
   * @param array $args
   *
   * @return string
   */
  private function construct(string $class, array $args): string {
    return CodeGen::phpConstruct($class, array_map(
      fn ($arg) => var_export($arg, TRUE),
      $args,
    ));
  }

  /**
   * @param string $message
   *
   * @return never
   *
   * @throws \Donquixote\CodegenTools\Exception\CodegenException
   */
  private function fail(string $message): never {
    throw new CodegenException($message);
  }

}
