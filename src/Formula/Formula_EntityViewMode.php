<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\DID\Attribute\Parameter\CallService;
use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;
use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\renderkit\TextLookup\TextLookup_EntityType;

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
