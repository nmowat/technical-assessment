<?php

namespace Drupal\flood_report;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FloodReportService {

  /**
   * The HTTP client to fetch the feed data with.
   *
   * @var \GuzzleHttp\Client
   */
  protected Client $httpClient;

  /**
   * Constructor for FloodReportService.
   *
   * @param Client $httpClient
   */
  public function __construct(Client $httpClient) {
    $this->httpClient = $httpClient;
  }

  /**
   * Retrieve list of Stations.
   *
   * @return array
   * @throws GuzzleException
   */
  public function getStations(): array {
    // Fetch stations from the API.
    $options = $data = [];
    $url = 'https://environment.data.gov.uk/flood-monitoring/id/stations?_limit=50';

    try {
      $response = $this->httpClient->request('GET', $url);
      $data = json_decode($response->getBody()->getContents(), TRUE);
    }
    catch (Exception $e) {
      \Drupal::logger('flood_report_getStations')->error($e->getMessage());
    }

    if ($data) {
      foreach ($data['items'] as $item) {
        if (!empty($item['label'])) {
          // Key the options by the individual id.
          $array = explode('/', $item['@id']);
          $key = end($array);
          // Ensure consistent formatting of station names.
          $options[$key] = ucwords(strtolower($item['label']));
        }
      }
      // Alphabetically sort the options.
      asort($options);
    }
    return $options;
  }

  /**
   * Retrieve information for a selected Station.
   *
   * @param $id
   * @return array
   * @throws GuzzleException
   */
  public function getStation($id): array {
    // Fetch the results for a specific station from the API via its ID.
    $url = 'https://environment.data.gov.uk/flood-monitoring/id/stations/' . $id . '/readings?_sorted&_limit=10';
    $output = $data = [];

    try {
      $response = $this->httpClient->request('GET', $url);
      $data = json_decode($response->getBody()->getContents(), TRUE);
    }
    catch (Exception $e) {
        \Drupal::logger('flood_report_getStation')->error($e->getMessage());
    }

    if ($data) {
      foreach ($data['items'] as $key => $item) {
        // Extract output where we have both required values.
        if (!empty($item['dateTime']) && !empty($item['value'])) {
          // Convert date to user-friendly format.
          $date = date('d M Y G:i', strtotime($item['dateTime']));
          $output[$key]['dateTime'] = $date;
          $output[$key]['value'] = number_format((float)$item['value'], 3, '.', '');
        }

      }
      json_encode($output);
    }

    return $output;
  }

}
