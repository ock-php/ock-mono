<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_SelectSchemaBase;

/**
 * Schema for values of the structure $view_id . ':' . $view_display_id
 */
class CfSchema_ViewIdWithDisplayId_WithLink extends CfSchema_Drilldown_SelectSchemaBase {

  /**
   * @param \Drupal\renderkit8\Schema\CfSchema_ViewIdWithDisplayId $optionsSchema
   */
  public function __construct(CfSchema_ViewIdWithDisplayId $optionsSchema) {
    parent::__construct($optionsSchema);
  }

  /**
   * @param string|int $id
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($id) {

    list($viewId, $displayId) = explode(':', $id) + [NULL, NULL];

    return new CfSchema_ViewsDisplayEditLink($viewId, $displayId);
  }

  /**
   * @return string
   */
  public function getIdKey() {
    // @todo Support this kind of drilldown, where the options form is only for documentation.
    return NULL;
  }

  /**
   * @return string
   */
  public function getOptionsKey() {
    return NULL;
  }
}
