<?php
declare(strict_types=1);

namespace Drupal\ock\Element;

use Drupal\Core\Render\Attribute\RenderElement;
use Drupal\Core\Render\Element\RenderElementBase;
use Drupal\ock\UI\Controller\Controller_ReportIface;
use Ock\Ock\Util\StringUtil;

#[RenderElement('ock_drilldown_container')]
class RenderElement_DrilldownContainer extends RenderElementBase {

  /**
   * {@inheritdoc}
   */
  public function getInfo(): array {
    return [
      '#theme_wrappers' => ['container'],
      '#process' => ['form_process_container'],
      '#ock_interface' => NULL,
      '#ock_context' => NULL,
      /* @see _ock_pre_render_drilldown_container() */
      '#pre_render' => [[self::class, 'preRender']],
    ];
  }

  /**
   * @param array $element
   *
   * @return array
   */
  public static function preRender(array $element): array {

    $interface = $element['#ock_interface'];
    $interface_label = StringUtil::interfaceGenerateLabel($interface);
    $replacements = ['@type' => $interface_label];

    $element['#attributes']['data:ock_interface'] = $interface;

    $tools = [];

    $tools['copy']['#markup']
      = '<a class="ock-copy">' . t('Copy "@type" configuration (local storage)', $replacements) . '</a>';

    $tools['paste']['#markup']
      = '<a class="ock-paste">' . t('Paste "@type" configuration (local storage)', $replacements) . '</a>';

    $tools[]['#markup'] = '<hr/>';

    # $tools[]['#markup'] = '<strong>' . check_plain($interface_label) . '</strong>';

    if (\Drupal::currentUser()->hasPermission('view ock reports')) {

      $tools['inspect'] = [
        '#type' => 'link',
        // Will be replaced on client side.
        '#title' => t('About "@name" plugin'),
        '#url' => Controller_ReportIface::route($interface)->url(),
        '#attributes' => [
          'class' => ['ock-inspect'],
          'target' => '_blank',
          'title' => t('About this plugin.'),
        ],
      ];

      $tools['report'] = [
        '#type' => 'link',
        '#title' => t('About "@type" plugins', $replacements),
        '#url' => Controller_ReportIface::route($interface)->url(),
        '#attributes' => [
          'target' => '_blank',
          'title' => t('Definitions for @type plugins.', $replacements),
        ],
      ];

      $tools['demo'] = [
        '#type' => 'link',
        '#title' => t('Demo / Code generator.'),
        '#url' => Controller_ReportIface::route($interface, 'demo')->url(),
        '#attributes' => [
          'class' => ['ock-demo'],
          'target' => '_blank',
          'title' => t('Demo / Code generator.'),
        ],
      ];
    }

    $element['tools'] = [
      '#weight' => -999,
      '#type' => 'container',
      '#attributes' => [
        'class' => ['ock-tools'],
        'style' => 'display: none;',
      ],
      'top' => [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'ock-tools-handle',
        ],
        '#children' => t('tools'),
      ],
      'items' => $tools + [
          '#theme_wrappers' => ['container'],
          '#attributes' => [
            'class' => ['ock-tools-dropdown'],
          ],
        ],
    ];

    return $element;
  }

}
