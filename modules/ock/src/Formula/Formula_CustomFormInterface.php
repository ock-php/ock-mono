<?php

declare(strict_types=1);

namespace Drupal\ock\Formula;

use Drupal\ock\Formator\FormatorD8Interface;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Summarizer\SummarizerInterface;

interface Formula_CustomFormInterface extends FormulaInterface, FormatorD8Interface, SummarizerInterface {

}
