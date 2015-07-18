<?php

namespace Drupal\renderkit\EntityImage;

use Drupal\renderkit\EntityDisplay\UserDisplayBase;

/**
 * Image provider based on user picture.
 */
class UserPicture extends UserDisplayBase implements EntityImageInterface {

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
      return array();
    }
    $alt = t("@user's picture", array('@user' => format_username($user)));
    return array(
      '#theme' => 'image',
      '#path' => $image_path,
      '#alt' => $alt,
      '#title' => $alt,
    );
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
