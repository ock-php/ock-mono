## Multiline inside inline

Original php code:

```php
use Donquixote\CodegenTools\Tests\Fixtures\GenericObject;

return GenericObject::f(
  GenericObject::f('short intro', ['long string that should be broken down', 'another long string which follows the previous string which came before it'], 'short ending'),
  GenericObject::f('first line text which is too long for it all to be inline', ['long string that should be broken down', 'another long string which follows the previous string which came before it'], 'b'),
  GenericObject::f('a', ['long string that should be broken down', 'another long string which follows the previous string which came before it'], 'last line text which is too long as a last line'),
  GenericObject::f('first line text which is too long for it all to be inline', ['short string 1', 'short string 2'], 'b'),
  GenericObject::f('short intro', ['short string 1', 'short string 2'], 'b'),
  ['short intro', ['long string that should be broken down', 'another long string which follows the previous string which came before it'], 'short ending'],
);
```

Prettified code:

```php
use Donquixote\CodegenTools\Tests\Fixtures\GenericObject;

return GenericObject::f(
  GenericObject::f('short intro', [
    'long string that should be broken down',
    'another long string which follows the previous string which came before it',
  ], 'short ending'),
  GenericObject::f(
    'first line text which is too long for it all to be inline',
    [
      'long string that should be broken down',
      'another long string which follows the previous string which came before it',
    ],
    'b',
  ),
  GenericObject::f(
    'a',
    [
      'long string that should be broken down',
      'another long string which follows the previous string which came before it',
    ],
    'last line text which is too long as a last line',
  ),
  GenericObject::f(
    'first line text which is too long for it all to be inline',
    ['short string 1', 'short string 2'],
    'b',
  ),
  GenericObject::f('short intro', ['short string 1', 'short string 2'], 'b'),
  ['short intro', [
    'long string that should be broken down',
    'another long string which follows the previous string which came before it',
  ], 'short ending'],
);
```
