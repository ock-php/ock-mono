<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\ock\Attribute\DI\CallServiceMethod;
use Drupal\ock\Attribute\DI\RegisterService;
use Drupal\renderkit\TextLookup\TextLookup_EntityType;

class Formula_EntityFormMode extends Formula_EntityViewMode {

  #[RegisterService]
  public static function create(
    #[CallServiceMethod('entity_type.manager', 'getStorage', ['entity_form_mode'])]
    ConfigEntityStorageInterface $viewModeEntityStorage,
    #[GetService]
    TextLookup_EntityType $entityTypeLabelLookup,
  ): self {
    return (new self($viewModeEntityStorage))
      ->withGroupByPrefix($entityTypeLabelLookup);
  }

}
