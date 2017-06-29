<?php

namespace Drupal\renderkit\EntityImage;

use Drupal\cfrreflection\Configurator\Configurator_CallbackSimple;
use Drupal\renderkit\EntityDisplay\UserDisplayTrait;

/**
 * Image provider based on user picture.
 */
class EntityImage_UserPicture implements EntityImageInterface {

  use UserDisplayTrait;

  /**
   * @CfrPlugin(
   *   id = "user_picture",
   *   label = @Translate("User picture")
   * )
   *
   * @param string $entityType
   *   Entity type from context.
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createConfigurator($entityType = NULL) {
    if (NULL !== $entityType && 'user' !== $entityType) {
      return NULL;
    }
    return Configurator_CallbackSimple::createFromClassName(__CLASS__);
  }

  /**
   * @param object $user
   *
   * @return array
   *   Render array for the user object.
   */
  protected function buildUser($user) {
    /* @see template_preprocess_user_picture() */
    $image_path = $this->userGetImagePath($user);
    if (!$image_path) {
      return [];
    }
    $alt = t("@user's picture", ['@user' => format_username($user)]);
    return [
      '#theme' => 'image',
      '#path' => $image_path,
      '#alt' => $alt,
      '#title' => $alt,
    ];
  }

  /**
   * @param object $user
   *
   * @return string
   */
  protected function userGetImagePath($user) {
    if (!empty($user->picture->uri)) {
      return $user->picture->uri;
    }
    return variable_get('user_picture_default', '');
  }
}
