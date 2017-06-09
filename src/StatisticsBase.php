<?php

namespace Drupal\kifistats;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

abstract class StatisticsBase implements StatisticsEngineInterface {
  use StringTranslationTrait;

  public function setParameters(array $parameters) {
    $this->parameters = $parameters;
  }

  protected function getParameter($key, $default = NULL) {
    return isset($this->parameters[$key]) ? $this->parameters[$key] : $default;
  }
}
