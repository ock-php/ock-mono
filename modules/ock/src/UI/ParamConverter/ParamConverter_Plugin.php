<?php
declare(strict_types=1);

namespace Drupal\ock\UI\ParamConverter;

use Drupal\Core\Utility\Error;
use Drupal\ock\Attribute\DI\ServiceTags;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\DID\Attribute\Service;
use Ock\Ock\Exception\PluginListException;
use Ock\Ock\Plugin\Map\PluginMapInterface;
use Ock\Ock\Plugin\NamedPlugin;
use Psr\Log\LoggerInterface;

#[Service(self::class)]
#[ServiceTags(['paramconverter'])]
class ParamConverter_Plugin extends ParamConverterBase {

  public const TYPE = 'ock:plugin';

  /**
   * Constructor.
   *
   * @param \Ock\Ock\Plugin\Map\PluginMapInterface $pluginMap
   *   Plugin map.
   * @param \Psr\Log\LoggerInterface $logger
   *   Logger.
   */
  public function __construct(
    #[GetService]
    private readonly PluginMapInterface $pluginMap,
    private readonly LoggerInterface $logger,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function convert($value, $definition, $name, array $defaults): ?NamedPlugin {
    $interface = $defaults['interface'] ?? '';
    if ($interface === '') {
      return null;
    }
    if (!is_string($interface)) {
      throw new \RuntimeException('Interface must be a string.');
    }
    try {
      $plugins = $this->pluginMap->typeGetPlugins($interface);
    }
    catch (PluginListException $e) {
      // @todo Inject the logger.
      Error::logException($this->logger, $e);
    }
    $plugin = $plugins[$value] ?? null;
    if (!$plugin) {
      return null;
    }

    return new NamedPlugin($value, $plugin);
  }
}
