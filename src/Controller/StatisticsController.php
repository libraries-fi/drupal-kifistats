<?php

namespace Drupal\kifistats\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\kifistats\StatisticsManager;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class StatisticsController extends ControllerBase {
  protected $statisticsManager;

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('kifistats.statistics_manager'),
      $container->get('module_handler'),
      $container->get('form_builder')
    );
  }

  public function __construct(StatisticsManager $statistics, ModuleHandlerInterface $modules, FormBuilderInterface $form_builder) {
    $this->statisticsManager = $statistics;
    $this->moduleManager = $modules;
    $this->formBuilder = $form_builder;
  }

  public function index() {
    $engines = $this->statisticsManager->getEngines();

    $build = [
      '#theme' => 'table',
      '#header' => ['title' => $this->t('Title'), 'id' => $this->t('ID'), 'module' => $this->t('Module')],
      '#rows' => []
    ];

    foreach ($engines as $engine) {
      list($_, $module) = explode('\\', get_class($engine));

      $build['#rows'][] = [
        'title' => [
          'data' => [
            '#type' => 'link',
            '#url' => Url::fromRoute('kifistats.view', ['statistics_id' => $engine->getId()]),
            '#title' => $engine->getTitle(),
          ]
        ],
        'id' => $engine->getId(),
        'module' => $this->moduleManager->getName($module),
      ];
    }

    return $build;
  }

  public function title($statistics_id) {
    $engine = $this->statisticsManager->getEngine($statistics_id);
    return $engine->getTitle();
  }

  public function view(Request $request, $statistics_id) {
    $engine = $this->statisticsManager->getEngine($statistics_id);
    $engine->setParameters($request->query->all());

    $form = $this->formBuilder->getForm('Drupal\kifistats\Form\StatisticsForm', $engine);
    $result = $engine->execute();

    $build = [
      '#theme' => 'kifistats_statistics__' . $statistics_id,
      '#form' => $form,
      '#result' => $result + ['#type' => 'container'],
    ];

    return $build;
  }
}
