<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Discovery\DocToAnnotations;

interface DocToAnnotationsInterface {

  /**
   * @param string $docComment
   *
   * @return array[]
   */
  public function docGetAnnotations(string $docComment): array;

}
