<?php

namespace Drupal\flood_report;

use GuzzleHttp\Client;

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
   * Make an API request and return the JSON response as an array.
   *
   * @param string $url
   *   The URL of the API endpoint.
   *
   * @return array|null
   *   The parsed JSON response as an array.
   */
  public function makeApiRequestJson($url) {
    try {
      $response = $this->httpClient->request('GET', $url);
      $data = json_decode($response->getBody()->getContents(), TRUE);

      return $data;
    }
    catch (\Exception $e) {
      // Handle exceptions here.
      return NULL;
    }
  }

  /**
   * Retrieve list of Stations.
   *
   * @return mixed
   */
  public function getStations() {
    // Fetch stations from the API.
    $url = 'https://environment.data.gov.uk/flood-monitoring/id/stations?_limit=50';
    return $this->httpClient->makeApiRequestJson($url);
  }

  /**
   * Retrieve information for a selected Station.
   * @param $id
   * @return mixed
   */
  public function getStation($id) {
    // Implement logic to fetch station readings from the API.
    $url = "https://environment.data.gov.uk/flood-monitoring/id/stations/' . $id . '/readings?_sorted&_limit=10";
    return $this->httpClient->makeApiRequestJson($url);
  }

}
