<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\renderkit\TextLookup\TextLookup_EntityType;
use Ock\DID\Attribute\Parameter\CallService;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\DID\Attribute\Service;

class Formula_EntityViewMode extends Formula_ConfigEntityIdGrouped {

  #[Service(self::class)]
  public static function create(
    #[CallService(args: ['entity_view_mode'])]
    ConfigEntityStorageInterface $viewModeEntityStorage,
    #[GetService]
    TextLookup_EntityType $entityTypeLabelLookup,
  ): self {
    return (new self($viewModeEntityStorage))
      ->withGroupByPrefix($entityTypeLabelLookup);
  }

  /**
   * @param string $entityType
   *
   * @return static
   */
  public function withEntityType(string $entityType): static {
    return $this->withProperty('targetEntityType', $entityType);
  }

}
