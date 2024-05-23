<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Ock\Ock\Attribute\Parameter\OckFormulaFromCall;
use Ock\Ock\Attribute\Parameter\OckFormulaFromClass;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;
use Ock\Ock\Formula\Boolean\Formula_Boolean_YesNo;
use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\BuildProcessor\BuildProcessor_Container;
use Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessor_Wrapper_LinkToEntity;
use Drupal\renderkit\EntityDisplay\Decorator\EntityDisplay_WithBuildProcessor;
use Drupal\renderkit\EntityDisplay\Decorator\EntityDisplay_WithEntityBuildProcessor;
use Drupal\renderkit\Formula\Formula_TagName;

/**
 * The most boring entity display handler, ever.
 *
 * @CfrPlugin(
 *   id = "rawTitle",
 *   label = "Entity title, raw"
 * )
 */
#[OckPluginInstance('rawTitle', 'Title, raw')]
class EntityDisplay_Title extends EntityDisplayBase {

  /**
   * Static factory for the advanced formula.
   *
   * @param string|null $wrapperTagName
   * @param bool $link
   *
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  #[OckPluginInstance('title', 'Title')]
  public static function create(
    #[OckOption('tag_name', 'Wrapper')]
    #[OckFormulaFromCall([Formula_TagName::class, 'create'], [['h1', 'h2', 'h3', 'h4', 'strong'], NULL])]
    ?string $wrapperTagName,
    #[OckOption('link', 'Link to entity')]
    #[OckFormulaFromClass(Formula_Boolean_YesNo::class)]
    bool $link,
  ): EntityDisplayInterface {
    $display = new self();
    if ($link) {
      $display = new EntityDisplay_WithEntityBuildProcessor(
        $display,
        new EntityBuildProcessor_Wrapper_LinkToEntity(),
      );
    }
    if ($wrapperTagName) {
      $display = new EntityDisplay_WithBuildProcessor(
        $display,
        BuildProcessor_Container::create($wrapperTagName),
      );
    }
    return $display;
  }

  /**
   * {@inheritdoc}
   */
  public function buildEntity(EntityInterface $entity): array {
    return [
      '#markup' => $entity->label(),
    ];
  }

}
