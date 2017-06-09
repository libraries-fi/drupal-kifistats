<?php

namespace Drupal\kifistats;

use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class StatisticsManager {
  protected $engines = [];

  public function addEngine(StatisticsEngineInterface $provider) {
    $this->engines[$provider->getId()] = $provider;
  }

  public function getEngine($id) {
    if (!isset($this->engines[$id])) {
      throw new ServiceNotFoundException($id);
    }
    return $this->engines[$id];
  }

  public function getEngines() {
    return $this->engines;
  }
}
