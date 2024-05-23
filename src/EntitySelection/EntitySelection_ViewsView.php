<?php

declare(strict_types = 1);

namespace Drupal\renderkit\EntitySelection;

use Donquixote\DID\Attribute\Parameter\CallService;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * @template-extends EntitySelection_All<\Drupal\views\Entity\View>
 */
class EntitySelection_ViewsView extends EntitySelection_All {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   */
  public function __construct(
    #[CallService(args: ['view'])]
    EntityStorageInterface $storage,
  ) {
    parent::__construct($storage);
  }

}
