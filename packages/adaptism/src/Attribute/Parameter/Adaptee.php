<?php
declare(strict_types=1);

namespace Ock\Adaptism\Attribute\Parameter;

/**
 * Marks a parameter as the adaptee object.
 *
 * The parameter type must be a class or interface.
 *
 * @see \Ock\Adaptism\Attribute\Adapter
 */
#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
final class Adaptee {

}
