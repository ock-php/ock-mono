<?php

namespace Drupal\renderkit8\EntityImage;

use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProvider_Callback;
use Drupal\renderkit8\EntityDisplay\UserDisplayTrait;

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
   * @param string|null $entityType
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public static function createSchema($entityType = NULL) {

    if (NULL !== $entityType && 'user' !== $entityType) {
      return NULL;
    }

    return CfSchema_ValueProvider_Callback::fromClass(__CLASS__);
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
