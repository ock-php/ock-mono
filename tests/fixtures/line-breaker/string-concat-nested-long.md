## String concat nested long

Original php code:

```php
return 'This string is too long for a single line. ' . ('The character limit forces a line break.' . ' And more text.') . ('and more' . 'more more');
```

Prettified code:

```php
return 'This string is too long for a single line. '
  . ('The character limit forces a line break.' . ' And more text.')
  . ('and more' . 'more more');
```
