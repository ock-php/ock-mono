<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterMap;

interface AdapterMapInterface {

  /**
   * @param class-string|null $adapteeType
   * @param class-string|null $resultType
   *
   * @return \Iterator<\Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface>
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public function getSuitableAdapters(?string $adapteeType, ?string $resultType): \Iterator;

}
