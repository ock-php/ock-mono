## New c fqcn

Original PHP:

```php
return new \Ock\CodegenTools\Tests\Fixtures\C();
```

Formatted as snippet:

```php
use Ock\CodegenTools\Tests\Fixtures\C;

return new C();
```

Formatted as file:

```php
<?php

declare(strict_types=1);

use Ock\CodegenTools\Tests\Fixtures\C;

return new C();
```
