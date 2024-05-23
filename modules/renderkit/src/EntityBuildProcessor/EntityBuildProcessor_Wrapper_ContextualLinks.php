<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityBuildProcessor;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Text\Text;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\renderkit\Formula\Formula_ClassAttribute;
use Drupal\renderkit\Formula\Formula_TagName;
use Drupal\renderkit\Html\HtmlTagTrait;

/**
 * A typical entity container with contextual links and stuff.
 *
 * @CfrPlugin(
 *   id = "contextualLinksWrapperDefault",
 *   label = "Entity contextual links wrapper, default"
 * )
 */
class EntityBuildProcessor_Wrapper_ContextualLinks implements EntityBuildProcessorInterface {

  use HtmlTagTrait;

  /**
   * @CfrPlugin("contextualLinksWrapper", "Entity contextual links wrapper")
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function createCfrFormula(): FormulaInterface {
    return Formula::group()
      ->add(
        'tag_name',
        Text::t('Tag name'),
        Formula_TagName::createForContainer('article'),
      )
      ->add(
        'classes',
        Text::t('Classes'),
        Formula_ClassAttribute::create(),
      )
      ->call([self::class, 'createFromGroupValues']);
  }

  /**
   * @param array $values
   *
   * @return self
   */
  public static function createFromGroupValues(array $values): self {
    return (new self())
      ->setTagName($values['tag_name'])
      ->addClasses($values['classes']);
  }

  public function __construct() {
    $this->setTagName('article');
  }

  /**
   * @param array $build
   *   Render array before the processing.
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *   Render array after the processing.
   */
  public function processEntityBuild(array $build, EntityInterface $entity): array {
    return $this->entityBuildContainer($entity) + [
      'content' => $build,
    ];
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  private function entityBuildContainer(EntityInterface $entity): array {

    $container = $this->buildContainer();

    if ([] === $links = $this->entityBuildContextualLinks($entity)) {
      return $container;
    }

    $container['#attributes']['class'][] = 'contextual-region';

    $container['contextual_links'] = [
      '#type' => 'contextual_links_placeholder',
      '#id' => _contextual_links_to_id($links),
    ];

    return $container;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array[]
   */
  private function entityBuildContextualLinks(EntityInterface $entity): array {

    /* @see \Drupal\node\NodeViewBuilder::alterBuild() */
    /* @see \Drupal\taxonomy\TermViewBuilder::alterBuild() */

    if (!$etid = $entity->id()) {
      return [];
    }

    $entityTypeId = $entity->getEntityTypeId();

    $link = [];
    $link['route_parameters'][$entityTypeId] = $etid;

    if ($entity instanceof EntityChangedInterface) {
      $link['metadata']['changed'] = $entity->getChangedTime();
    }

    $group = $entityTypeId;

    if ($entity instanceof RevisionableInterface) {
      if (!$entity->isDefaultRevision()) {
        $group = $entityTypeId . '_revision';
        $link['route_parameters'][$entityTypeId . '_revision'] = $entity->getRevisionId();
      }
    }

    return [$group => $link];
  }
}