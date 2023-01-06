## Find services

Executed PHP:

```php
use Donquixote\DID\ServiceDefinition\ServiceDefinitionList_Discovery;
use Donquixote\DID\Tests\Fixtures\Services\EntryPoints;
use Donquixote\DID\Util\PhpUtil;

$generator = new \Donquixote\DID\Generator\Generator();

$itemsPhp = [];
foreach (EntryPoints::getServiceDefinitionList()->getDefinitions() as $definition) {
  $itemsPhp[$definition->id] = $generator->generate($definition->valueDefinition);
}

$expression = PhpUtil::phpArray($itemsPhp);

return PhpUtil::formatExpressionAsSnippet($expression);
```

Return value:

```php
use Donquixote\DID\Tests\Fixtures\Services\SiteConfig;
use Donquixote\DID\Tests\Fixtures\Services\Translator\Translator;
use Donquixote\DID\Tests\Fixtures\Services\WordLookup;

return [
  'Donquixote\\DID\\Tests\\Fixtures\\Services\\SiteConfig' => new SiteConfig(),
  'Donquixote\\DID\\Tests\\Fixtures\\Services\\Translator\\Translator' => new Translator(
    $container->get('Donquixote\\DID\\Tests\\Fixtures\\Services\\WordLookupInterface'),
  ),
  'Donquixote\\DID\\Tests\\Fixtures\\Services\\WordLookupInterface' => new WordLookup(
    $container->get('Donquixote\\DID\\Tests\\Fixtures\\Services\\SiteConfig'),
  ),
];
```
