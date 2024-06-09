<div>
<div style="position: sticky; top: 0; z-index: 99; background: white;">

```yml
test: 'Ock\DependencyInjection\Tests\SymfonyContainerTest::testSymfonyContainer()'
'dataset name': AsAliasAttribute
arguments:
  -
    class: Ock\ClassDiscovery\NamespaceDirectory
    $directory: /var/www/html/packages/dependency-injection/tests/src/Fixtures/AsAliasAttribute
    $terminatedNamespace: Ock\DependencyInjection\Tests\Fixtures\AsAliasAttribute\
  - inspector.default.php
```
</div>

## Values

### Classes

```yml
- Ock\DependencyInjection\Tests\Fixtures\AsAliasAttribute\PrivateAliasClass
- Ock\DependencyInjection\Tests\Fixtures\AsAliasAttribute\PrivateAliasInterface
- Ock\DependencyInjection\Tests\Fixtures\AsAliasAttribute\PublicAliasClass
- Ock\DependencyInjection\Tests\Fixtures\AsAliasAttribute\PublicAliasInterface
```

### Facts

```yml
-
  Ock\DependencyInjection\Tests\Fixtures\AsAliasAttribute\PrivateAliasInterface:
    class: Symfony\Component\DependencyInjection\Alias
-
  Ock\DependencyInjection\Tests\Fixtures\AsAliasAttribute\PrivateAliasClass:
    class: Symfony\Component\DependencyInjection\Definition
-
  Ock\DependencyInjection\Tests\Fixtures\AsAliasAttribute\PublicAliasInterface:
    class: Symfony\Component\DependencyInjection\Alias
    isPrivate(): false
    isPublic(): true
-
  Ock\DependencyInjection\Tests\Fixtures\AsAliasAttribute\PublicAliasClass:
    class: Symfony\Component\DependencyInjection\Definition
```

### Definitions before compile

```yml
service_container:
  getClass(): Symfony\Component\DependencyInjection\ContainerInterface
  isAutoconfigured(): false
  isAutowired(): false
  isPublic(): true
  isSynthetic(): true
Ock\DependencyInjection\Tests\Fixtures\AsAliasAttribute\PrivateAliasClass: {  }
Ock\DependencyInjection\Tests\Fixtures\AsAliasAttribute\PublicAliasClass: {  }
```

### Aliases before compile

```yml
Ock\DependencyInjection\Tests\Fixtures\AsAliasAttribute\PrivateAliasInterface: {  }
Ock\DependencyInjection\Tests\Fixtures\AsAliasAttribute\PublicAliasInterface:
  isPrivate(): false
  isPublic(): true
```

### Services

```yml
service_container:
  class: Symfony\Component\DependencyInjection\ContainerBuilder
Ock\DependencyInjection\Tests\Fixtures\AsAliasAttribute\PublicAliasInterface:
  class: Ock\DependencyInjection\Tests\Fixtures\AsAliasAttribute\PublicAliasClass
```
</div>
