<?php

namespace Drupal\flood_report\Controller;

use GuzzleHttp\Psr7\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\flood_report\FloodReportService;

class FloodReportController extends ControllerBase {

  protected $floodReportService;

  public function __construct(FloodReportService $floodReportService) {
    $this->floodReportService = $floodReportService;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('flood_report.station_service')
    );
  }

  public function redirectToStationEndpoint($id) {
    // Return response with the selected station ID.
    return new Response('Selected station ID: ' . $id);
  }
}
