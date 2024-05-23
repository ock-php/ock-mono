<?php
declare(strict_types=1);

namespace Drupal\ock\Util;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Render\Markup;
use Drupal\devel\DevelDumperManagerInterface;
use Drupal\ock\UI\Markup\Markup_DefinitionList;
use Ock\Ock\Util\HtmlUtil;

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
      // Impossible exception.
      throw new \RuntimeException($e->getMessage(), 0, $e);
    }

    $rows = [];

    $rows[] = [
      t('Exception message'),
      '<pre>' . HtmlUtil::sanitize($e->getMessage()) . '</pre>',
    ];

    $rows[] = [
      t('Exception'),
      t('@class thrown in line %line of @file', [
        '@class' => new FormattableMarkup('<code>@class</code><br/>', [
          '@class' => $e_class_reflection->getShortName()
        ]),
        '%line' => $e->getLine(),
        '@file' => new FormattableMarkup('<code>@basename</code><br/><code>@file</code>', [
          '@basename' => basename($file),
          '@file' => $file,
        ]),
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
      throw new \RuntimeException($e->getMessage(), 0, $e);
    }

    return [
      'text' => [
        '#markup' => (new Markup_DefinitionList())
          ->addDt(t('Exception in line %line of %file', [
            '%line' => $e->getLine(),
            '%file' => basename($file)
          ]))
          ->addDd(new FormattableMarkup('<code>@code</code>', ['@code' => $file]))
          ->addDt(t('Exception class: %class', ['%class' => $e_class_reflection->getShortName()]))
          ->addDd(HtmlUtil::sanitize($e_class))
          ->addDt(t('Exception message:'))
          ->addDd(new FormattableMarkup('<pre>@code</pre>', ['@code' => $e->getMessage()])),
      ],
      'trace_label' => [
        '#markup' => '<div>' . t('Exception stack trace') . ':</div>',
      ],
      'trace' => self::dumpData(BacktraceUtil::exceptionGetRelativeNicetrace($e)),
    ];
  }

  /**
   * @param mixed $data
   * @param string|\Drupal\Component\Render\MarkupInterface $fieldset_label
   *
   * @return array
   */
  public static function dumpDataInFieldset(mixed $data, string|MarkupInterface $fieldset_label): array {

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
  public static function dumpData(mixed $data): array {

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
  public static function dumpValue(mixed $v): ?string {

    if (!\is_object($v) && !\is_array($v)) {
      return '<pre>' . var_export($v, TRUE) . '</pre>';
    }

    /** @var \Drupal\devel\DevelDumperManagerInterface $dumper */
    $dumper = \Drupal::service('devel.dumper');
    if ($dumper instanceof DevelDumperManagerInterface) {
      return $dumper->export($v);
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
