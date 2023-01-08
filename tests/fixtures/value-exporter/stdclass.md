## Stdclass

Value:

```php
$obj = new \stdClass();
$obj->x = 5;
$obj->y = 'hello';
return $obj;
```

Exported value:

```php
return (object) ['x' => 5, 'y' => 'hello'];
```
