## New c fqcn

Original PHP:

```php
return new \Donquixote\CodegenTools\Tests\Fixtures\C();
```

Formatted as snippet:

```php
use Donquixote\CodegenTools\Tests\Fixtures\C;

return new C();
```

Formatted as file:

```php
<?php

declare(strict_types=1);

use Donquixote\CodegenTools\Tests\Fixtures\C;

return new C();
```
