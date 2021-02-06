<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaBase;

interface CfSchemaBase_AbstractIdInterface {

  /**
   * @param string|int $id
   *
   * @return string|null
   */
  public function idGetLabel($id): ?string;

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown($id): bool;

}
