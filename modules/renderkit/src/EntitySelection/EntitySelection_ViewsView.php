<?php

declare(strict_types = 1);

namespace Drupal\renderkit\EntitySelection;

use Drupal\Core\Entity\EntityStorageInterface;
use Ock\DependencyInjection\Attribute\Parameter\GetParametricService;

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
    #[GetParametricService('view')]
    EntityStorageInterface $storage,
  ) {
    parent::__construct($storage);
  }

}
