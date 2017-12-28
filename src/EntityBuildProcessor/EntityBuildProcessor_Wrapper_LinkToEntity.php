<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityBuildProcessor;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityMalformedException;
use Drupal\Core\Entity\Exception\UndefinedLinkTemplateException;
use Drupal\renderkit8\BuildProcessor\BuildProcessor_Container;
use Drupal\renderkit8\Html\HtmlAttributesTrait;
use Drupal\renderkit8\Schema\CfSchema_TagName;
use Drupal\themekit\Element\RenderElement_ThemekitLinkWrapper;

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
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function entityTitleSchema(): CfSchemaInterface {
    return CfSchema_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'entityTitleLinkWrapper',
      [CfSchema_TagName::createForTitle()],
      [t('Tag name')]);
  }

  /**
   * @param string $tagName
   *
   * @return \Drupal\renderkit8\EntityBuildProcessor\EntityBuildProcessorInterface
   */
  public static function entityTitleLinkWrapper($tagName): EntityBuildProcessorInterface {
    return (new EntityBuildProcessor_Sequence)
      ->addEntityBuildProcessor(new self)
      ->addBuildProcessor(BuildProcessor_Container::create($tagName));
  }

  /**
   * @param array $build
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *   Render array for one entity.
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
      '#type' => RenderElement_ThemekitLinkWrapper::ID,
      'content' => $build,
      '#url' => $url,
      // @todo Optionally set $attributes[title] with $entity->label() ?
      '#attributes' => $this->attributes,
    ];
  }

}
