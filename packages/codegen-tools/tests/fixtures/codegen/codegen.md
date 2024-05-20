## Codegen

Executed PHP:

```php
use Ock\CodegenTools\Tests\Fixtures\GenericObject;
use Ock\CodegenTools\Util\CodeFormatUtil;
use Ock\CodegenTools\Util\CodeGen;

$expression = CodeGen::phpArray([
  CodeGen::phpArray(["'a'", "'b'"]),
  CodeGen::phpArray([
    "'zero'",
    "'one - overwritten'",
    3 => "'three'",
    1 => "'one'",
    "'four'",
  ]),
  "'a'",
  5,
  'key' => "'value'",
  CodeGen::phpCall('strtolower', ["'Hello'"]),
  // Prove that last parameter is optional.
  CodeGen::phpCall('time'),
  CodeGen::phpCall([GenericObject::class, 'fooStatic'], ["'hello'"]),
  CodeGen::phpCallFqn('(new \\' . GenericObject::class . '())->fooStatic', ["'hello'"]),
  CodeGen::phpCallFqn('(new \\' . GenericObject::class . '())->fooStatic', ["'hello'"], true),
  CodeGen::phpConstruct(GenericObject::class, ["'hello'"]),
  CodeGen::phpConstruct(GenericObject::class, ["'hello'"], true),
  CodeGen::phpConstructDynamic('\\' . GenericObject::class . "::class . ''", ["'hello'"]),
  CodeGen::phpConstructDynamic('\\' . GenericObject::class . "::class . ''", ["'hello'"], true),
  CodeGen::phpEncloseIf('5', true),
  CodeGen::phpEncloseIf('5', false),
  CodeGen::phpCallMethod('(new \\' . GenericObject::class . '())', 'foo', ["'hello'"]),
  CodeGen::phpCallStatic([GenericObject::class, 'fooStatic'], ["'hello'"]),
  CodeGen::phpCallFunction('ucfirst', ["'hello'"]),
  'time' . CodeGen::phpArglist([]),
  CodeGen::phpValueUnchecked(5),
  CodeGen::phpValue(5),
  CodeGen::phpValue(new \stdClass()),
  CodeGen::phpScalar(null),
]);

return CodeFormatUtil::formatExpressionAsSnippet($expression);
```

Return value:

```php
use Ock\CodegenTools\Tests\Fixtures\GenericObject;

return [
  ['a', 'b'],
  ['zero', 'one', 3 => 'three', 4 => 'four'],
  'a',
  5,
  'key' => 'value',
  \strtolower('Hello'),
  \time(),
  GenericObject::fooStatic('hello'),
  (new GenericObject())->fooStatic('hello'),
  ((new GenericObject())->fooStatic('hello')),
  new GenericObject('hello'),
  (new GenericObject('hello')),
  new (GenericObject::class . '')('hello'),
  (new (GenericObject::class . '')('hello')),
  (5),
  5,
  (new GenericObject())->foo('hello'),
  GenericObject::fooStatic('hello'),
  \ucfirst('hello'),
  time(),
  5,
  5,
  (object) [],
  NULL,
];
```
