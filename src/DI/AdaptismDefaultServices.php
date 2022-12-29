<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\DI;

use Donquixote\DID\ParamToCTV\ParamToCTV_Attribute_CallService;
use Donquixote\DID\ParamToCTV\ParamToCTV_Attribute_CallServiceMethod;
use Donquixote\DID\ParamToCTV\ParamToCTV_Attribute_GetService;
use Donquixote\DID\ParamToCTV\ParamToCTV_Chain;
use Donquixote\DID\ParamToCTV\ParamToCTVInterface;
use Donquixote\DID\Attribute\Service;

/**
 * @see \Donquixote\Adaptism\Tests\Fixtures\FixturesDefaultServices
 */
class AdaptismDefaultServices {

  /**
   * @return \Donquixote\DID\ParamToCTV\ParamToCTVInterface
   */
  #[Service]
  public static function getParamToCTV(): ParamToCTVInterface {
    return new ParamToCTV_Chain([
      new ParamToCTV_Attribute_GetService(),
      new ParamToCTV_Attribute_CallService(),
      new ParamToCTV_Attribute_CallServiceMethod(),
    ]);
  }

}
