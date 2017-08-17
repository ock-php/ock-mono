<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Boolean\CfSchema_Boolean_YesNo;
use Donquixote\Cf\Schema\Group\CfSchema_Group_V2VBase;
use Drupal\renderkit8\BuildProcessor\BuildProcessor_Container;
use Drupal\renderkit8\EntityBuildProcessor\EntityBuildProcessor_Wrapper_LinkToEntity;
use Drupal\renderkit8\EntityDisplay\Decorator\EntityDisplay_WithBuildProcessor;
use Drupal\renderkit8\EntityDisplay\Decorator\EntityDisplay_WithEntityBuildProcessor;
use Drupal\renderkit8\EntityDisplay\EntityDisplay_Title;

class CfSchema_EntityDisplay_Title extends CfSchema_Group_V2VBase {

  /**
   * @var string[]
   */
  private $allowedTagNames;

  /**
   * @return self
   */
  public static function create() {
    return new self(['h1', 'h2', 'h3', 'h4', 'strong']);
  }

  /**
   * TitleEntityDisplayPlugin constructor.
   *
   * @param string[] $allowedTagNames
   */
  public function __construct(array $allowedTagNames) {
    $this->allowedTagNames = array_combine($allowedTagNames, $allowedTagNames);
  }

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface[]
   *   Format: $[$groupItemKey] = $groupItemSchema
   */
  public function getItemSchemas() {
    return [
      'tag_name' => CfSchema_TagName::createOptional(
        $this->allowedTagNames),
      'link' => new CfSchema_Boolean_YesNo(),
    ];
  }

  /**
   * @return string[]
   */
  public function getLabels() {
    return [
      t('Wrapper'),
      t('Link to entity'),
    ];
  }

  /**
   * @param mixed[] $values
   *   Format: $[$groupItemKey] = $groupItemValue
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function valuesGetValue(array $values) {

    $wrapperTagName = $values['tag_name'];
    $link = $values['link'];

    $display = new EntityDisplay_Title();

    if ($link) {
      $wrapper = new EntityBuildProcessor_Wrapper_LinkToEntity();
      $display = new EntityDisplay_WithEntityBuildProcessor($display, $wrapper);
    }

    if (NULL !== $wrapperTagName) {
      $container = new BuildProcessor_Container();
      $container->setTagName($wrapperTagName);
      $display = new EntityDisplay_WithBuildProcessor($display, $container);
    }

    return $display;
  }

  /**
   * @param string[] $itemsPhp
   *
   * @return string
   */
  public function itemsPhpGetPhp(array $itemsPhp) {

    $wrapperTagNamePhp = $itemsPhp['tag_name'];
    $linkPhp = $itemsPhp['link'];

    $display_php = 'new ' . EntityDisplay_Title::class . '()';

    if ($linkPhp === 'TRUE' || $linkPhp === 'true') {
      $display_php = 'new ' . EntityDisplay_WithEntityBuildProcessor::class . '('
        . "\n" . $display_php . ','
        . "\n" . 'new ' . EntityBuildProcessor_Wrapper_LinkToEntity::class . '()' . ')';
    }

    if ('NULL' !== $wrapperTagNamePhp && 'null' !== $wrapperTagNamePhp) {
      $container = '(new ' . BuildProcessor_Container::class . '())';
      $container .= '->setTagName(' . $wrapperTagNamePhp  . ')';
      $display_php = 'new ' . EntityDisplay_WithBuildProcessor::class . '('
        . "\n" . $display_php . ','
        . "\n" . $container . ')';
    }

    return $display_php;
  }

  /**
   * @param mixed $conf
   *
   * @return null|string
   *
   * @todo Support this?
   */
  public function _confGetSummary($conf) {
    list($wrapperTagName, $link) = $this->confGetNormalized($conf);

    if ($link && NULL !== $wrapperTagName) {
      return $wrapperTagName . ', ' . t('linked to entity') . '.';
    }
    elseif ($link) {
      return t('Linked to entity');
    }
    elseif (NULL !== $wrapperTagName) {
      return $wrapperTagName;
    }
    else {
      return t('Raw title');
    }
  }

  /**
   * @param mixed $conf
   *
   * @return array
   *   Format: [$tagName, $link]
   */
  private function confGetNormalized($conf) {

    if (!is_array($conf)) {
      $conf = [];
    }

    if (0
      || !isset($conf['tag_name'])
      || !array_key_exists($conf['tag_name'], $this->allowedTagNames)
    ) {
      $conf['tag_name'] = NULL;
    }

    $conf['link'] = !empty($conf['link']);

    return [$conf['tag_name'], $conf['link']];
  }
}
