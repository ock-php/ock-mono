## Invalid value definition type

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;

return new C(); 
```

Exception:

```php
throw new \InvalidArgumentException(
  'Objects must implement Donquixote\\DID\\ValueDefinition\\ValueDefinitionInterface. Found Donquixote\\DID\\Tests\\Fixtures\\C object.',
);
```
