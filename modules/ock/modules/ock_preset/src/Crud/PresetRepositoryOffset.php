<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Crud;

use Drupal\Core\Config\Config;
use Drupal\Core\Config\ImmutableConfig;

class PresetRepositoryOffset {

  /**
   * Constructor.
   *
   * @param \Drupal\ock_preset\Crud\PresetRepository $repository
   * @param string $interface
   */
  public function __construct(
    private readonly PresetRepository $repository,
    private readonly string $interface,
  ) {}

  /**
   * @return \Drupal\Core\Config\ImmutableConfig[]
   */
  public function loadAll(): array {
    return $this->repository->loadForInterface($this->interface);
  }

  /**
   * @param string $preset_name
   *
   * @return \Drupal\Core\Config\ImmutableConfig
   */
  public function load(string $preset_name): ImmutableConfig {
    return $this->repository->load(
      $this->interface,
      $preset_name);
  }

  /**
   * @param string $preset_name
   *
   * @return \Drupal\Core\Config\Config
   */
  public function loadEditable(string $preset_name): Config {
    return $this->repository->loadEditable(
      $this->interface,
      $preset_name,
    );
  }

}
