<?php
declare(strict_types=1);

namespace Drupal\cu\Util;

use Donquixote\ObCK\Util\HtmlUtil;
use Drupal\Core\Render\Markup;
use Drupal\devel\DevelDumperManagerInterface;

final class UiDumpUtil extends UtilBase {

  /**
   * @param \Exception $e
   *
   * @return string[][]
   */
  public static function exceptionGetTableRows(\Exception $e): array {

    $file = $e->getFile();
    $e_class = \get_class($e);
    try {
      $e_class_reflection = new \ReflectionClass($e_class);
    }
    catch (\ReflectionException $e) {
      throw new \RuntimeException("Impossible: Class '$e_class' not found.");
    }

    $rows = [];

    $rows[] = [
      t('Exception message'),
      HtmlUtil::sanitize($e->getMessage()),
    ];

    $rows[] = [
      t('Exception'),
      t(
        '@class thrown in line %line of @file',
        [
          '@class' => Markup::create(''
            . '<code>' . HtmlUtil::sanitize($e_class_reflection->getShortName()) . '</code>'
            . '<br/>'),
          '%line' => $e->getLine(),
          '@file' => Markup::create(''
            . '<code>' . HtmlUtil::sanitize(basename($file)) . '</code>'
            . '<br/>'
            . '<code>' . HtmlUtil::sanitize($file) . '</code>'),
        ]),
    ];

    $rows[] = [
      t('Stack trace'),
      self::dumpValue(BacktraceUtil::exceptionGetRelativeNicetrace($e)),
    ];

    return $rows;
  }

  /**
   * @param \Exception $e
   *
   * @return string
   *
   * @todo Currently unused?
   */
  public static function exceptionGetHtml(\Exception $e): string {
    $build = self::displayException($e);
    // @todo Make this a service, and inject the renderer.
    return \Drupal::service('renderer')->render($build);
  }

  /**
   * @param \Exception $e
   *
   * @return array
   */
  public static function displayException(\Exception $e): array {

    $file = $e->getFile();
    $e_class = \get_class($e);
    // We know that the class exists, because of get_class() above.
    try {
      $e_class_reflection = new \ReflectionClass($e_class);
    }
    catch (\ReflectionException $e) {
      throw new \RuntimeException("Impossible: Class '$e_class' not found.");
    }

    return [
      'text' => [
        '#markup' => ''
          // @todo This should probably be in a template. One day.
          . '<dl>'
          . '  <dt>' . t(
            'Exception in line %line of %file',
            [
              '%line' => $e->getLine(),
              '%file' => basename($file)
            ]
          ) . '</dt>'
          . '  <dd><code>' . HtmlUtil::sanitize($file) . '</code></dd>'
          . '  <dt>'
          . t('Exception class: %class', ['%class' => $e_class_reflection->getShortName()])
          . '</dt>'
          . '  <dd>' . HtmlUtil::sanitize($e_class) . '</dt>'
          . '  <dt>' . t('Exception message:') . '</dt>'
          . '  <dd><pre>' . HtmlUtil::sanitize($e->getMessage()) . '</pre></dd>'
          . '</dl>',
      ],
      'trace_label' => [
        '#markup' => '<div>' . t('Exception stack trace') . ':</div>',
      ],
      'trace' => self::dumpData(BacktraceUtil::exceptionGetRelativeNicetrace($e)),
    ];
  }

  /**
   * @param mixed $data
   * @param string $fieldset_label
   *
   * @return array
   */
  public static function dumpDataInFieldset($data, $fieldset_label): array {

    return self::dumpData($data)
      + [
        '#type' => 'fieldset',
        '#title' => $fieldset_label,
      ];
  }

  /**
   * @param mixed $data
   *
   * @return array
   */
  public static function dumpData($data): array {

    $element = [];

    if (\function_exists('dpm')) {
      /** @var \Drupal\devel\DevelDumperManagerInterface $dumper */
      $dumper = \Drupal::service('devel.dumper');
      $element['dump'] = $dumper->exportAsRenderable($data);
    }
    else {
      $element['notice']['#markup'] = t('No dump utility available. Install devel.');
    }

    return $element;
  }

  /**
   * @param mixed $v
   *
   * @return null|string
   */
  public static function dumpValue($v): ?string {

    if (!\is_object($v) && !\is_array($v)) {
      return '<pre>' . var_export($v, TRUE) . '</pre>';
    }

    /** @var \Drupal\devel\DevelDumperManagerInterface $dumper */
    $dumper = \Drupal::service('devel.dumper');
    if ($dumper instanceof DevelDumperManagerInterface) {
      return (string) $dumper->export($v);
    }

    if (\is_object($v)) {
      return (string) t(
        'Object of class @class.',
        ['@class' => Markup::create('<code>' . \get_class($v) . '</code>')]
      );
    }

    return '<pre>' . print_r($v, TRUE) . '</pre>';
  }
}
