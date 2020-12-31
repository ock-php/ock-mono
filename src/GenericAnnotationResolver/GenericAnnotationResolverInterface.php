<?php

namespace Donquixote\Cf\GenericAnnotationResolver;

interface GenericAnnotationResolverInterface {

  /**
   * @param string $name
   *   The annotation tag name, without the "@".
   * @param array $args
   *   Values from the annotation body.
   *
   * @return mixed
   */
  public function resolveAnnotation(string $name, array $args);

}
