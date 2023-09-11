<?php

namespace Drupal\flood_report;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\JsonResponse;

class FloodReportService {

  /**
   * The HTTP client to fetch the feed data with.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * Constructor for FloodReportService.
   *
   * @param \GuzzleHttp\Client $http_client
   *   A Guzzle client object.
   */
  public function __construct(Client $httpClient) {
    $this->httpClient = $httpClient;
  }

  /**
   * Retrieve list of Stations.
   *
   * @return mixed
   * @throws GuzzleException
   */
  public function getStations() {
    // Fetch stations from the API.
    $options = [];
    $url = 'https://environment.data.gov.uk/flood-monitoring/id/stations?_limit=50';

    try {
      $response = $this->httpClient->request('GET', $url);
      $data = json_decode($response->getBody()->getContents(), TRUE);
    }
    catch (Exception $exception) {
      // TODO: handle exception.
    }
    if ($data) {
      foreach($data['items'] as $key => $value) {
        if (!empty($value['catchmentName'])) {
          $options[$key] = $value['catchmentName'];
        }
      }
    }
    return $options;
  }

  /**
   * Retrieve information for a selected Station.
   * @param $id
   * @return mixed
   * @throws GuzzleException
   */
  public function getStation($id) {
    // Implement logic to fetch station readings from the API.
    $url = "https://environment.data.gov.uk/flood-monitoring/id/stations/' . $id . '/readings?_sorted&_limit=10";

    try {
      $response = $this->httpClient->request('GET', $url);
      $data = json_decode($response->getBody()->getContents(), TRUE);
    }
    catch (Exception $exception) {
      // TODO: handle exception.
    }
    if ($data) {

      var_dump($data);

      foreach($data['items'] as $key => $value) {
        if (!empty($value['catchmentName'])) {
          $options[$key] = $value['catchmentName'];
        }
      }
    }
    return $options;
  }

}
