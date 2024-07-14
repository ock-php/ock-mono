<?php
declare(strict_types=1);

namespace Drupal\ock\UI\ParamConverter;

use Drupal\Core\Utility\Error;
use Ock\Ock\Exception\PluginListException;
use Ock\Ock\Plugin\Map\PluginMapInterface;
use Ock\Ock\Plugin\NamedPlugin;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AutoconfigureTag('paramconverter')]
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
    private readonly PluginMapInterface $pluginMap,
    #[Autowire(service: 'logger.channel.ock')]
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
