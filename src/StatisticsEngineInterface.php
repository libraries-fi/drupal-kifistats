<?php

namespace Drupal\kifistats;

use Drupal\Core\Form\FormStateInterface;

interface StatisticsEngineInterface {
  public function getId();
  public function getTitle();
  public function execute();

  public function setParameters(array $parameters);
  public function alterForm(array &$form, FormStateInterface $form_state);
  public function buildUrlQuery(FormStateInterface $form_state);
}
