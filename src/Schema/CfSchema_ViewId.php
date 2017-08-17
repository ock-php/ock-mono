<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Options\Flat\CfSchema_FlatOptionsInterface;
use Drupal\views\Views;

class CfSchema_ViewId implements CfSchema_FlatOptionsInterface {

  /**
   * @var bool|null
   */
  private $status;

  /**
   * @param bool|null $status
   */
  public function __construct($status = true) {
    $this->status = $status;
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  public function getOptions() {

    $options = [];
    foreach ($this->getViews() as $key => $view) {
      $options[$view->id()] = $view->label();
    }

    ksort($options);

    return $options;
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {

    if (NULL === $view = $this->idGetView($id)) {
      return NULL;
    }

    return $view->label();
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  public function idIsKnown($id) {
    return NULL !== $view = $this->idGetView($id);
  }

  /**
   * @param string $id
   *
   * @return \Drupal\views\Entity\View|null
   */
  private function idGetView($id) {

    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $etm */
    $etm = \Drupal::service('entity_type.manager');

    /** @var \Drupal\Core\Entity\EntityStorageInterface $storage */
    $storage = $etm->getStorage('view');

    /** @var null|\Drupal\views\Entity\View $view */
    if (NULL === $view = $storage->load($id)) {
      return NULL;
    }

    if (1
      && NULL !== $this->status
      && $view->status() !== $this->status
    ) {
      return NULL;
    }

    return $view;
  }

  /**
   * @return \Drupal\views\Entity\View[]
   */
  private function getViews() {

    if (NULL === $this->status) {
      return Views::getAllViews();
    }
    elseif (FALSE === $this->status) {
      return Views::getDisabledViews();
    }
    else {
      return Views::getEnabledViews();
    }
  }
}
