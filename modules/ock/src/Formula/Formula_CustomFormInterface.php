<?php

declare(strict_types=1);

namespace Drupal\ock\Formula;

use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Summarizer\SummarizerInterface;
use Drupal\ock\Formator\FormatorD8Interface;

interface Formula_CustomFormInterface extends FormulaInterface, FormatorD8Interface, SummarizerInterface {

}
