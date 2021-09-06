<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityBuildProcessor;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupVal_Callback;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityMalformedException;
use Drupal\Core\Entity\Exception\UndefinedLinkTemplateException;
use Drupal\renderkit\BuildProcessor\BuildProcessor_Container;
use Drupal\renderkit\Html\HtmlAttributesTrait;
use Drupal\renderkit\Formula\Formula_TagName;

/**
 * Wraps the content from a decorated display handler into a link, linking to
 * the entity.
 *
 * Just like the base class, this does provide methods like addClass() to modify
 * the attributes of the link element.
 *
 * A typical use case would be to wrap in image into a link element.
 *
 * @CfrPlugin(
 *   id = "entityLinkWrapper",
 *   label = @t("Entity link wrapper")
 * )
 */
class EntityBuildProcessor_Wrapper_LinkToEntity implements EntityBuildProcessorInterface {

  use HtmlAttributesTrait;

  /**
   * @CfrPlugin(
   *   id = "entityTitleLinkWrapper",
   *   label = @t("Entity title link wrapper")
   * )
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function entityTitleFormula(): FormulaInterface {
    return Formula_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'entityTitleLinkWrapper',
      [Formula_TagName::createForTitle()],
      [t('Tag name')]);
  }

  /**
   * @param string $tagName
   *
   * @return \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface
   */
  public static function entityTitleLinkWrapper($tagName): EntityBuildProcessorInterface {
    return (new EntityBuildProcessor_Sequence)
      ->addEntityBuildProcessor(new self)
      ->addBuildProcessor(BuildProcessor_Container::create($tagName));
  }

  /**
   * {@inheritdoc}
   */
  public function processEntityBuild(array $build, EntityInterface $entity): array {

    try {
      $url = $entity->toUrl();
    }
    catch (UndefinedLinkTemplateException $e) {
      // @todo Log this.
      unset($e);
      return $build;
    }
    catch (EntityMalformedException $e) {
      // @todo Log this.
      unset($e);
      return $build;
    }

    return [
      '#type' => 'link',
      '#url' => $url,
      '#title' => $build,
    ];
  }

}
