<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Crud;

class PresetRepositoryOffset {

  /**
   * @var \Drupal\ock_preset\Crud\PresetRepository
   */
  private $repository;

  /**
   * @var string
   */
  private $interface;

  /**
   * @param \Drupal\ock_preset\Crud\PresetRepository $repository
   * @param string $interface
   */
  public function __construct(PresetRepository $repository, $interface) {
    $this->repository = $repository;
    $this->interface = $interface;
  }

  /**
   * @return \Drupal\Core\Config\ImmutableConfig[]
   */
  public function loadAll() {
    return $this->repository->loadForInterface($this->interface);
  }

  /**
   * @param string $preset_name
   *
   * @return \Drupal\Core\Config\ImmutableConfig
   */
  public function load($preset_name) {
    return $this->repository->load(
      $this->interface,
      $preset_name);
  }

  /**
   * @param string $preset_name
   *
   * @return \Drupal\Core\Config\Config
   */
  public function loadEditable($preset_name) {
    return $this->repository->loadEditable(
      $this->interface,
      $preset_name);
  }

}
