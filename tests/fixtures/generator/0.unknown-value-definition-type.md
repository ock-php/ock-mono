## Unknown value definition type

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\UnknownValueDefinition;

return new UnknownValueDefinition(); 
```

Exception:

```php
throw new \RuntimeException(
  'Unknown value definition type Donquixote\\DID\\Tests\\Fixtures\\UnknownValueDefinition.',
);
```
