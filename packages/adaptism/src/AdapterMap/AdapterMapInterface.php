<?php

declare(strict_types=1);

namespace Ock\Adaptism\AdapterMap;

interface AdapterMapInterface {

  /**
   * @param class-string|null $adapteeType
   * @param class-string|null $resultType
   *
   * @return \Iterator<\Ock\Adaptism\SpecificAdapter\SpecificAdapterInterface>
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
   */
  public function getSuitableAdapters(?string $adapteeType, ?string $resultType): \Iterator;

}
