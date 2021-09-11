<?php

declare(strict_types=1);

namespace Drupal\cu\Formula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Summarizer\SummarizerInterface;
use Drupal\cu\Formator\FormatorD8Interface;

interface Formula_CustomFormInterface extends FormulaInterface, FormatorD8Interface, SummarizerInterface {

}
