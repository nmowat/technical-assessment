<?php

namespace Drupal\flood_report\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\flood_report\FloodReportService;

class FloodReportForm extends FormBase {

  protected $floodReportService;

  public function __construct(FloodReportService $floodReportService) {
    $this->floodReportService = $floodReportService;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('flood_report.station_service')
    );
  }

  public function getFormId() {
    return 'flood_report_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    // Get the list of stations from the service.
    $stations = $this->floodReportService->getStations();
    // TODO: Parse response.
    $options = [];


    // Add the select options to the form.
    $form['station'] = [
      '#type' => 'select',
      '#title' => $this->t('Select a Flood Report station'),
      '#options' => $options,
      ];

    // Add a submit button to the form.
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit')
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Get the selected station ID from the submitted form values.
    $selectedStationId = $form_state->getValue('station');

    // Redirect to the endpoint with the selected station ID.
    $url = Url::fromRoute('flood_report.station_controller', ['id' => $selectedStationId]);
    $response = new TrustedRedirectResponse($url->toString());
    $response->send();
  }
}
