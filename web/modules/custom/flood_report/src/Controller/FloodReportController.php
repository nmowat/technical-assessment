<?php

namespace Drupal\flood_report\Controller;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\flood_report\FloodReportService;

class FloodReportController extends ControllerBase {

  protected FloodReportService $floodReportService;

  /**
   * FloodReportController constructor.
   *
   * @param FloodReportService $floodReportService
   */
  public function __construct(FloodReportService $floodReportService) {
    $this->floodReportService = $floodReportService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): FloodReportController|static {
    return new static(
      $container->get('flood_report.station_service')
    );
  }

  /**
   * Retrieve results for a selected station, output as a render array table.
   *
   * @throws GuzzleException
   */
  public function getStationResults($id): array {

    $json_data = $this->floodReportService->getStation($id);
    $header = $rows = [];
    if ($json_data) {
      $header = [
        'col1' => t('Date & Time'),
        'col2' => t('Value'),
      ];
      foreach ($json_data as $item) {
        $rows[] = [
          $item['dateTime'], $item['value']
        ];
      }
    }

    if ($rows) {
      return [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $rows,
      ];
    }
    else {
      return [
        '#type' => 'markup',
        '#markup' => '<p>' . t('No information available at the moment') . '</p>',
      ];
    }

  }

}
