## String concat nested with calls

Original php code:

```php
return 'This string is too long for a single line. ' . ('The character limit forces a line break.' . ' And more text.') . strtoupper('and more text which is in an argument' . 'more more longer text which will be on next line');
```

Prettified code:

```php
return 'This string is too long for a single line. '
  . ('The character limit forces a line break.' . ' And more text.')
  . strtoupper(
    'and more text which is in an argument'
      . 'more more longer text which will be on next line',
  );
```
