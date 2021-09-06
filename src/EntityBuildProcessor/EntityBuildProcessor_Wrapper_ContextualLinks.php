<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityBuildProcessor;

use Donquixote\ObCK\Formula\Group\Formula_Group;
use Donquixote\ObCK\Formula\ValueToValue\Formula_ValueToValue_CallbackMono;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\renderkit\Html\HtmlTagTrait;
use Drupal\renderkit\Formula\Formula_ClassAttribute;
use Drupal\renderkit\Formula\Formula_TagName;

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
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function createCfrFormula() {

    $groupFormula = new Formula_Group(
      [
        'tag_name' => Formula_TagName::createForContainer('article'),
        'classes' => Formula_ClassAttribute::create(),
      ],
      [
        'tag_name' => t('Tag name'),
        'classes' => t('Classes'),
      ]);

    return Formula_ValueToValue_CallbackMono::fromStaticMethod(
      __CLASS__,
      'createFromGroupValues',
      $groupFormula);
  }

  /**
   * @param array $values
   *
   * @return self
   */
  public static function createFromGroupValues(array $values) {

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
  private function entityBuildContainer(EntityInterface $entity) {

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
  private function entityBuildContextualLinks(EntityInterface $entity) {

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
