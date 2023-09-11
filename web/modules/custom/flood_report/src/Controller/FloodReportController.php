<?php

namespace Drupal\flood_report\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\flood_report\FloodReportService;

class FloodReportController extends ControllerBase {

  protected $floodReportService;

  /**
   * FloodReportController constructor.
   *
   * @param \Drupal\flood_report\FloodReportService $floodReportService
   */
  public function __construct(FloodReportService $floodReportService) {
    $this->floodReportService = $floodReportService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('flood_report.station_service')
    );
  }

  /**
   * Fetch results from a selected station.
   */
  public function getStationResults($id) {

    $output = $this->floodReportService->getStation($id);
    return ($output);

  }

}
