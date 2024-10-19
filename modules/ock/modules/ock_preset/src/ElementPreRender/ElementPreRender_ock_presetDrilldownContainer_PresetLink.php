<?php

namespace Drupal\ock_preset\ElementPreRender;

use Drupal\Core\Security\TrustedCallbackInterface;
use Drupal\ock_preset\Controller\Controller_IfacePresets;

class ElementPreRender_ock_presetDrilldownContainer_PresetLink implements TrustedCallbackInterface {

  public static function trustedCallbacks() {
    return ['preRender'];
  }

  public static function preRender(array $element) {

    if (!\Drupal::currentUser()->hasPermission('administer ock_preset')) {
      return $element;
    }

    $interface = $element['#ock_preset_interface'];

    $element['tools']['items'][] = [
      '#markup' => '<hr/>',
    ];

    $element['tools']['items']['ock_preset-link'] = [
      '#type' => 'link',
      '#title' => t('Create a preset'),
      '#url' => Controller_IfacePresets::route($interface)
        ->subpage('add')
        ->url(),
      '#attributes' => [
        // 'style' => 'display: none',
        'class' => ['ock_preset-link'],
        'target' => '_blank',
        'title' => t('Create a new preset with this plugin configuration.'),
      ],
    ];

    $element['#attached']['library'][] = 'ock_preset/drilldown-additions';

    return $element;
  }

}
