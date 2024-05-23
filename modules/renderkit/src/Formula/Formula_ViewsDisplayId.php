<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\DID\Attribute\Parameter\CallService;
use Donquixote\Ock\Formula\Select\Formula_SelectInterface;
use Donquixote\Ock\Text\TextInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\ock\DrupalText;
use Drupal\views\Entity\View;

/**
 * Formula for values of the structure "$viewId.$viewDisplayId".
 */
class Formula_ViewsDisplayId implements Formula_SelectInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   */
  public function __construct(
    #[CallService(args: ['view'])]
    private readonly EntityStorageInterface $storage,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getOptionsMap(): array {
    $map = [];
    /** @var \Drupal\views\Entity\View $view */
    foreach ($this->storage->loadMultiple() as $view) {
      $viewId = $view->id();
      $displays = $view->get('display');
      $defaultDisplay = $displays['default'];
      foreach ($displays as $displayId => $display) {
        if (!$this->checkDisplay($view, $displayId, $defaultDisplay, $display)) {
          continue;
        }
        $map[$viewId . '.' . $displayId] = $viewId;
      }
    }
    return $map;
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(int|string $id): bool {
    [$viewId, $displayId] = explode('.', $id);
    /** @var \Drupal\views\Entity\View|null $view */
    $view = $this->storage->load($viewId);
    if ($view === NULL) {
      return FALSE;
    }
    $displays = $view->get('display');
    $defaultDisplay = $displays['default'];
    $display = $displays[$displayId] ?? NULL;
    if ($display === NULL) {
      return FALSE;
    }
    return $this->checkDisplay($view, $displayId, $defaultDisplay, $display);
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(int|string $id): ?TextInterface {
    [$viewId, $displayId] = explode('.', $id);
    /** @var \Drupal\views\Entity\View|null $view */
    $view = $this->storage->load($viewId);
    if ($view === NULL) {
      return NULL;
    }
    $displays = $view->get('display');
    $display = $displays[$displayId] ?? NULL;
    if ($display === NULL) {
      return NULL;
    }
    // Ignore display conditions.
    // It is ok to return a label even if the id is not allowed.
    return DrupalText::s('@view: @display (@type)', [
      '@view' => DrupalText::fromVar($view->label()),
      '@display' => $display['display_title'],
      '@type' => $display['display_plugin'],
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function groupIdGetLabel(int|string $groupId): ?TextInterface {
    /** @var \Drupal\views\Entity\View|null $view */
    $view = $this->storage->load($groupId);
    if ($view === NULL) {
      return NULL;
    }
    // @todo Also add the type of entities shown in this view.
    return DrupalText::fromVar($view->label());
  }

  /**
   * @param \Drupal\views\Entity\View $view
   * @param string $displayId
   * @param array $display
   *
   * @return bool
   */
  protected function checkDisplay(View $view, string $displayId, array $defaultDisplay, array $display): bool {
    return (bool) ($display['enabled'] ?? TRUE);
  }

}
