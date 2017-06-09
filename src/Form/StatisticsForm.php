<?php

namespace Drupal\kifistats\Form;

use InvalidArgumentException;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;
use Drupal\kifistats\StatisticsEngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StatisticsForm extends FormBase {
  protected $routeMatch;
  protected $engine;

  public static function create(ContainerInterface $container) {
    return new static($container->get('current_route_match'));
  }

  public function __construct(RouteMatchInterface $route_match) {
    $this->routeMatch = $route_match;
  }

  public function getFormId() {
    return 'kifistats_statistics_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, StatisticsEngineInterface $engine = NULL) {
    if (is_null($engine)) {
      throw new InvalidArgumentException('Passing a valid statistics engine is required.');
    }

    $this->engine = $engine;
    $engine->alterForm($form, $form_state);

    $form['actions'] = [
      '#type' => 'container',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Apply'),
      ],
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $route = $this->routeMatch->getRouteName();
    $params = $this->routeMatch->getParameters()->all();
    $query = $this->engine->buildUrlQuery($form_state);

    $form_state->setRedirect($route, $params, ['query' => $query]);
  }
}
