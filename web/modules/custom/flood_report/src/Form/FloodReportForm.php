<?php

namespace Drupal\flood_report\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\flood_report\FloodReportService;

class FloodReportForm extends FormBase {

  protected FloodReportService $floodReportService;

  public function __construct(FloodReportService $floodReportService) {
    $this->floodReportService = $floodReportService;
  }

  public static function create(ContainerInterface $container): FloodReportForm|static {
    return new static(
      $container->get('flood_report.station_service')
    );
  }

  public function getFormId(): string {
    return 'flood_report_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {

    // Get select options of the stations from the service.
    $options = $this->floodReportService->getStations();

    // Add the select options to the form.
    $form['station'] = [
      '#type' => 'select',
      '#title' => $this->t('Select a Flood Report station to see the 10 most recent results:'),
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
    $form_state->setRedirect('flood_report.controller', [
      'id' => $selectedStationId,
    ]);
  }

}
