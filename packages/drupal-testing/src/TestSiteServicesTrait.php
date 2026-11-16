<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting;

use Drupal\Component\Serialization\Yaml;

/**
 * Registers services in the site under test, without a module.
 *
 * Services are added to the services.yml of the test site, the same way as
 * core registers its own test services.
 *
 * @phpstan-require-extends \Drupal\Tests\BrowserTestBase
 *
 * @see \Drupal\Core\Test\FunctionalTestSetupTrait::prepareSettings()
 */
trait TestSiteServicesTrait {

  /**
   * Registers a service in the services.yml of the test site.
   *
   * This must be called from prepareSettings(), after the parent method.
   *
   * @param string $id
   *   Service id.
   * @param array<string, mixed> $definition
   *   Service definition, as in a services.yml file.
   */
  protected function registerTestSiteService(string $id, array $definition): void {
    $file = DRUPAL_ROOT . '/' . $this->siteDirectory . '/services.yml';
    $contents = file_get_contents($file);
    if ($contents === false) {
      throw new \RuntimeException("Cannot read '$file'.");
    }
    $services = Yaml::decode($contents);
    if (!is_array($services) || !is_array($services['services'] ??= [])) {
      throw new \RuntimeException("Unexpected contents in '$file'.");
    }
    $services['services'][$id] = $definition;
    file_put_contents($file, Yaml::encode($services));
  }

}
