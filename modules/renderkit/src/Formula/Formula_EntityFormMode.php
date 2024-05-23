<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\renderkit\TextLookup\TextLookup_EntityType;
use Ock\DID\Attribute\Parameter\CallService;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\DID\Attribute\Service;

class Formula_EntityFormMode extends Formula_EntityViewMode {

  #[Service]
  public static function create(
    #[CallService(args: ['entity_form_mode'])]
    ConfigEntityStorageInterface $viewModeEntityStorage,
    #[GetService]
    TextLookup_EntityType $entityTypeLabelLookup,
  ): self {
    return (new self($viewModeEntityStorage))
      ->withGroupByPrefix($entityTypeLabelLookup);
  }

}
