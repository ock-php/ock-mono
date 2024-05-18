<?php

declare(strict_types=1);

namespace Ock\Egg\Exception;

use Psr\Container\ContainerExceptionInterface;

/**
 * Exception thrown when an egg fails to hatch.
 *
 * @see \Ock\Egg\Egg\EggInterface::hatch()
 */
class EggHatchException extends \Exception implements ContainerExceptionInterface {

}
