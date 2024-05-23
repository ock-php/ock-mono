<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityBuildProcessor;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityMalformedException;
use Drupal\Core\Entity\Exception\UndefinedLinkTemplateException;
use Drupal\renderkit\BuildProcessor\BuildProcessor_Container;
use Drupal\renderkit\Formula\Formula_TagName;
use Drupal\renderkit\Html\HtmlAttributesTrait;
use Ock\Ock\Attribute\Parameter\OckFormulaFromCall;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * Wraps content from a decorated display into a link to the entity.
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
   * @param string $tagName
   *
   * @return \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface
   */
  #[OckPluginInstance('entityTitleLinkWrapper', 'Wrap in title tag, link to entity.')]
  public static function entityTitleLinkWrapper(
    #[OckOption('tag_name', 'Tag name')]
    #[OckFormulaFromCall([Formula_TagName::class, 'createForTitle'])]
    string $tagName,
  ): EntityBuildProcessorInterface {
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
    catch (UndefinedLinkTemplateException|EntityMalformedException $e) {
      // @todo More detailed message.
      watchdog_exception('renderkit', $e);
      return $build;
    }
    return [
      '#type' => 'link',
      '#url' => $url,
      '#title' => $build,
    ];
  }

}
