<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Donquixote\Ock\Text\TextInterface;
use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\ock\DrupalText;
use Drupal\views\Entity\View;
use Drupal\views\Views;

class Formula_ViewId implements Formula_FlatSelectInterface {

  /**
   * Constructor.
   *
   * @param bool|null $status
   *   TRUE for only enabled views, FALSE for only disabled views.
   */
  public function __construct(
    private readonly ?bool $status = TRUE,
  ) {}

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  public function getOptions(): array {
    $options = [];
    foreach ($this->getViews() as $view) {
      $options[$view->id()] = $view->label();
    }
    ksort($options);
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {
    if (NULL === $view = $this->idGetView($id)) {
      return NULL;
    }
    return DrupalText::fromEntity($view);
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown(string|int $id): bool {
    return NULL !== $this->idGetView($id);
  }

  /**
   * @param string $id
   *
   * @return \Drupal\views\Entity\View|null
   */
  private function idGetView(string $id): ?View {

    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $etm */
    $etm = \Drupal::service('entity_type.manager');

    /** @var \Drupal\Core\Entity\EntityStorageInterface $storage */
    try {
      $storage = $etm->getStorage('view');
    }
    catch (PluginException $e) {
      // @todo Log this.
      unset($e);
      return null;
    }

    /** @var null|\Drupal\views\Entity\View $view */
    if (NULL === $view = $storage->load($id)) {
      return NULL;
    }

    if ($this->status !== NULL && $view->status() !== $this->status) {
      return NULL;
    }

    return $view;
  }

  /**
   * @return \Drupal\views\Entity\View[]
   */
  private function getViews(): array {

    if (NULL === $this->status) {
      return Views::getAllViews();
    }

    if (FALSE === $this->status) {
      return Views::getDisabledViews();
    }

    return Views::getEnabledViews();
  }
}
