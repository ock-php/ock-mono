<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Boolean\Formula_Boolean_YesNo;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Text\Text;
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
class EntityDisplay_Title extends EntityDisplayBase {

  /**
   * Advanced plugin formula with additional options.
   *
   * @CfrPlugin(
   *   id = "title",
   *   label = "Entity title"
   * )
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function createAdvancedFormula(): FormulaInterface {
    return Formula::group()
      ->add(
        'tag_name',
        Text::t('Wrapper'),
        Formula_TagName::createOptional(
          ['h1', 'h2', 'h3', 'h4', 'strong']))
      ->add(
        'link',
        Text::t('Link to entity'),
        new Formula_Boolean_YesNo())
      ->call([self::class, 'create']);
  }

  /**
   * Static factory for the advanced formula.
   *
   * @param string|null $wrapperTagName
   * @param bool $link
   *
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  public static function create(?string $wrapperTagName, bool $link): EntityDisplayInterface {

    $display = new self();

    if ($link) {
      $display = new EntityDisplay_WithEntityBuildProcessor(
        $display,
        new EntityBuildProcessor_Wrapper_LinkToEntity());
    }

    if ($wrapperTagName) {
      $display = new EntityDisplay_WithBuildProcessor(
        $display,
        BuildProcessor_Container::create($wrapperTagName));
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
