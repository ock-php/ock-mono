<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\DID\Attribute\Parameter\CallService;
use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;
use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\renderkit\TextLookup\TextLookup_EntityType;

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
