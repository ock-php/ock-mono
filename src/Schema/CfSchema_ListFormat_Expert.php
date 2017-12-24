<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Evaluator\EvaluatorInterface;
use Donquixote\Cf\Form\D8\FormatorD8Interface;
use Donquixote\Cf\Summarizer\SummarizerInterface;
use Donquixote\Cf\Util\PhpUtil;
use Drupal\renderkit8\ListFormat\ListFormat_ElementDefaults;

/**
 * This is inspired by Display suite.
 *
 * @see \Drupal\renderkit8\ListFormat\ListFormat_ElementDefaults
 */
class CfSchema_ListFormat_Expert implements EvaluatorInterface, FormatorD8Interface, SummarizerInterface, CfSchemaInterface {

  /**
   * @param mixed $conf
   * @param string $label
   *
   * @return array
   */
  public function confGetD8Form($conf, ?string $label): array {

    $form = [
      '#type' => 'container',
      '#attributes' => ['class' => ['listformat-ui-expert-groups']],
      '#process' => [RENDERKIT_POP_PARENT],
    ];

    $wrappers = [
      'items' => t('List items'),
      'item' => t('List item'),
    ];

    /** @var string $k */

    foreach ($wrappers as $wrapper_key => $wrapper_label) {

      $form[$wrapper_key] = [
        '#type' => 'container',
        '#attributes' => ['class' => ['listformat-ui-expert-group']],
        '#process' => [RENDERKIT_POP_PARENT],
      ];

      foreach (['enabled', 'tag_name', 'classes'] as $colname) {
        $form[$wrapper_key][$colname] = [
          '#type' => 'container',
          '#process' => [RENDERKIT_POP_PARENT],
        ];
      }

      $form[$wrapper_key][$colname = 'enabled'][$k = $wrapper_key] = [
        '#type' => 'checkbox',
        '#title' => $wrapper_label,
        '#default_value' => !empty($conf[$k]),
      ];

      $form[$wrapper_key][$colname = 'tag_name'][$k = $wrapper_key . '-' . $colname] = [
        '#type' => 'textfield',
        '#size' => '10',
        '#title' => t('Element'),
        # '#description' => t('Leave empty for no tag around each item.'),
        '#default_value' => $conf[$k] ?? '',
        '#states' => [
          'visible' => [
            ':input[name$="[' . $wrapper_key . ']"]' => ['checked' => TRUE],
          ],
        ],
      ];

      $form[$wrapper_key][$colname = 'classes'][$k = $wrapper_key . '-' . $colname] = [
        '#type' => 'textfield',
        '#title' => t('Classes'),
        # '#description' => t('Classes separated by spaces. Has no effect if wrapper tag name is empty.'),
        '#default_value' => $conf[$k] ?? '',
        '#states' => [
          'visible' => [
            ':input[name$="[' . $wrapper_key . ']"]' => ['checked' => TRUE],
          ],
        ],
      ];
    }

    $form['item']['classes'][$k = 'zebra'] = [
      '#type' => 'checkbox',
      '#title' => t("Zebra striping ('even' / 'odd' classes)"),
      '#default_value' => !empty($conf[$k]),
      '#states' => [
        'visible' => [
          ':input[name$="[item]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['item']['classes'][$k = 'firstlast'] = [
      '#type' => 'checkbox',
      '#title' => t("Classes for 'first' / 'last'"),
      '#default_value' => !empty($conf[$k]),
      '#states' => [
        'visible' => [
          ':input[name$="[item]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form = [
      '#type' => 'fieldset',
      'groups' => $form,
    ];

    $form['#attached']['css'][] = drupal_get_path('module', 'renderkit8') . '/css/renderkit8.admin.css';

    return $form;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed|string|null
   */
  public function confGetSummary($conf) {
    // No summary details.
    return NULL;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return \Drupal\renderkit8\ListFormat\ListFormatInterface
   */
  public function confGetValue($conf) {
    $defaults = $this->confGetElementDefaults($conf);
    return new ListFormat_ElementDefaults($defaults);
  }

  /**
   * @param mixed $conf
   *
   * @return string
   */
  public function confGetPhp($conf): string {
    $defaults = $this->confGetElementDefaults($conf);
    return 'new ' . ListFormat_ElementDefaults::class . '('
      . "\n" . PhpUtil::phpValue($defaults) . ')';
  }

  /**
   * @param mixed $conf
   *
   * @return array
   *   Render array defaults.
   */
  private function confGetElementDefaults($conf) {

    if (!\is_array($conf)) {
      $conf = [];
    }

    $defaults = [];

    if (!empty($conf['items'])) {

      /* @see themekit_element_info() */
      /* @see theme_themekit_container() */
      $defaults['#type'] = 'themekit_container';

      $defaults['#tag_name'] = self::confExtractTagName($conf, 'items-tag-name', 'div');

      if (!empty($conf['items-classes'])) {
        if ([] !== $classes = array_unique(array_filter(explode(' ', $conf['items-classes'])))) {
          $defaults['#attributes']['class'] = $classes;
        }
      }
    }

    if (!empty($conf['item'])) {

      /* @see theme_themekit_item_containers() */
      $defaults['#theme'] = 'themekit_item_containers';

      $defaults['#item_tag_name'] = self::confExtractTagName($conf, 'item-tag-name', 'div');

      if (!empty($conf['item-classes'])) {
        if ([] !== $item_classes = array_unique(array_filter(explode(' ', $conf['item-classes'])))) {
          $defaults['#item_attributes']['class'] = $item_classes;
        }
      }

      if (!empty($conf['zebra'])) {
        $defaults['#zebra'] = TRUE;
      }

      if (!empty($conf['firstlast'])) {
        $defaults['#first'] = TRUE;
        $defaults['#last'] = TRUE;
      }
    }

    return $defaults;
  }

  /**
   * @param array $array
   * @param string $key
   * @param string $else
   *
   * @return string
   */
  private static function confExtractTagName(array $array, $key, $else) {

    if (empty($array[$key])) {
      return $else;
    }

    $tagName = trim($array[$key]);

    if ('' === $tagName) {
      return $else;
    }

    return $tagName;
  }
}
