<?php
declare(strict_types=1);

namespace Ock\Adaptism\Attribute\Parameter;

/**
 * Marks a parameter to receive the universal adapter object.
 *
 * This is needed if the adapter method wants to call other adapters.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
final class UniversalAdapter implements AdapterParameterAttributeInterface {

}
