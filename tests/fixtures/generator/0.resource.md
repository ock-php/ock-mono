## Resource

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;

return fopen((new \ReflectionClass(C::class))->getFileName(), 'r');
```

Exception:

```php
throw new \InvalidArgumentException('Unexpected value resource.');
```
