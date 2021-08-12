<?php
declare(strict_types=1);

namespace Drupal\cu\Element;

use Donquixote\OCUI\Util\StringUtil;
use Drupal\Core\Render\Element\RenderElement;
use Drupal\cu\Controller\Controller_ReportIface;

/**
 * @RenderElement("cu_drilldown_container")
 */
class RenderElement_DrilldownContainer extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo(): array {
    return [
      '#theme_wrappers' => ['container'],
      '#process' => ['form_process_container'],
      '#cu_interface' => NULL,
      '#cu_context' => NULL,
      /* @see _cu_pre_render_drilldown_container() */
      '#pre_render' => [[self::class, 'preRender']],
    ];
  }

  /**
   * @param array $element
   *
   * @return array
   */
  public static function preRender(array $element): array {

    $interface = $element['#cu_interface'];
    $interface_label = StringUtil::interfaceGenerateLabel($interface);
    $replacements = ['@type' => $interface_label];

    $element['#attributes']['data:cu_interface'] = $interface;

    $tools = [];

    $tools['copy']['#markup']
      = '<a class="cu-copy">' . t('Copy "@type" configuration (local storage)', $replacements) . '</a>';

    $tools['paste']['#markup']
      = '<a class="cu-paste">' . t('Paste "@type" configuration (local storage)', $replacements) . '</a>';

    $tools[]['#markup'] = '<hr/>';

    # $tools[]['#markup'] = '<strong>' . check_plain($interface_label) . '</strong>';

    if (\Drupal::currentUser()->hasPermission('view cu report')) {

      $tools['inspect'] = [
        '#type' => 'link',
        // Will be replaced on client side.
        '#title' => t('About "@name" plugin'),
        '#url' => Controller_ReportIface::route($interface)->url(),
        '#attributes' => [
          'class' => ['cu-inspect'],
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
          'class' => ['cu-demo'],
          'target' => '_blank',
          'title' => t('Demo / Code generator.'),
        ],
      ];
    }

    $element['tools'] = [
      '#weight' => -999,
      '#type' => 'container',
      '#attributes' => [
        'class' => ['cu-tools'],
        'style' => 'display: none;',
      ],
      'top' => [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'cu-tools-handle',
        ],
        '#children' => t('tools'),
      ],
      'items' => $tools + [
          '#theme_wrappers' => ['container'],
          '#attributes' => [
            'class' => ['cu-tools-dropdown'],
          ],
        ],
    ];

    return $element;
  }

}
