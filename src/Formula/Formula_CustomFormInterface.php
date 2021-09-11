<?php

declare(strict_types=1);

namespace Drupal\ock\Formula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Summarizer\SummarizerInterface;
use Drupal\ock\Formator\FormatorD8Interface;

interface Formula_CustomFormInterface extends FormulaInterface, FormatorD8Interface, SummarizerInterface {

}
