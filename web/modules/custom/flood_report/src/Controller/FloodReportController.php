<?php

namespace Drupal\flood_report\Controller;

use Drupal\Core\Link;
use Drupal\Core\Url;
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
        'col2' => t('Water height (metres)'),
      ];
      foreach ($json_data as $item) {
        $rows[] = [
          $item['dateTime'], $item['value']
        ];
      }
    }

    // Backlink to search.
    $url = Url::fromUri('base:/flood-report');
    $url->setOption('attributes', ['class' => ['btn-primary btn']]);
    $link = Link::fromTextAndUrl(t('Search again'), $url)->toString();

    // Return results in table, and append the backlink.
    if ($rows) {
      return [
        'table' => [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $rows,
          ],
        'back-link' => [
          '#type' => 'markup',
          '#markup' =>  $link,
        ],
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
