<?php

namespace Drupal\database_practice\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\mysql\Driver\Database\mysql\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Event Dashboard routes.
 */
class EventDashboardController extends ControllerBase {

  /**
   * This is to store the database connection object.
   *
   * @var \Drupal\mysql\Driver\Database\mysql\Connection
   */
  protected Connection $connection;

  /**
   * This is to construct the object of EventDashBoardController object.
   *
   * @param \Drupal\mysql\Driver\Database\mysql\Connection $connection
   *   The database connection object.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
    );
  }

  /**
   * Builds the response.
   */
  public function build() {
    $date_res = $this->dateQuery();
    $type_res = $this->typeQuery();
    if ($type_res && $date_res) {
      $yearly = $this->countYearly($date_res);
      $quarterly = $this->countQuarterly($date_res);
      $quarter_name = ['JAN-MAR', 'APR-JUN', 'JUL-SEPT', 'OCT-DEC'];
      $build['content'] = [
        '#theme' => 'event-dashboard',
        '#attached' => [
          'library' => ['database_practice/event-dashboard'],
        ],
        '#yearly' => $yearly,
        '#quarterly' => $quarterly,
        '#quarter_name' => $quarter_name,
        '#type_wise_event' => $type_res,
      ];
    }
    else {
      $build['content'] = [
        '#markup' => $this->t('NO EVENTS.'),
      ];
    }

    $build['content'] += [
      '#cache' => [
        'tags' => ['node_list:event', 'taxonomy_term_list:event_type'],
      ],
    ];
    // dd($build);
    return $build;
  }

  /**
   * This function calculate the number of events in each year.
   *
   * @param array $res
   *   Accepts the result of the date query to calculate the events per year.
   *
   * @return array
   *   Returns the yearly count array.
   */
  public function countYearly(array $res) {
    $years = [];
    foreach ($res as $item) {
      $year = date('Y', strtotime($item->field_date_of_event_value));
      if (!isset($years[$year])) {
        $years[$year] = 1;
      }
      else {
        $years[$year]++;
      }
    }
    return $years;
  }

  /**
   * This function calculate the number of events in each quarter of each year.
   *
   * @param array $res
   *   Accepts the result of the date query to calculate the events per quarter.
   *
   * @return array
   *   Returns the quarterly count array of each year.
   */
  public function countQuarterly(array $res) {
    $quarter = [];
    foreach ($res as $item) {
      $year = date('Y', strtotime($item->field_date_of_event_value));
      $month = date('m', strtotime($item->field_date_of_event_value));
      if (!isset($quarter[$year])) {
        $quarter[$year] = [];
      }
      if ($month >= 1 && $month <= 3) {
        $quarter[$year][0] = isset($quarter[$year][0]) ? $quarter[$year][0] + 1 : 1;
      }
      elseif ($month >= 4 && $month <= 6) {
        $quarter[$year][1] = isset($quarter[$year][1]) ? $quarter[$year][1] + 1 : 1;
      }
      elseif ($month >= 7 && $month <= 9) {
        $quarter[$year][2] = isset($quarter[$year][2]) ? $quarter[$year][2] + 1 : 1;
      }
      elseif ($month >= 10 && $month <= 12) {
        $quarter[$year][3] = isset($quarter[$year][3]) ? $quarter[$year][3] + 1 : 1;
      }
    }
    return $quarter;
  }

  /**
   * This function to fetch the data from the node__field_date_of_event.
   *
   * @return array
   *   Returns the result array.
   */
  public function dateQuery() {
    try {
      $query = $this->connection->select('node__field_date_of_event', 'date')
        ->fields('date', ['field_date_of_event_value']);
      $res = $query->execute()->fetchAll();
    }
    catch (\Exception $e) {
      $res = NULL;
    }

    return $res;
  }

  /**
   * This function to fetch the data from the node__field_type.
   *
   * @return array
   *   Returns the result array.
   */
  public function typeQuery() {
    try {
      $query = $this->connection->select('node__field_type', 'type');
      $query->join('taxonomy_term_field_data', 'taxonomy_term', 'type. field_type_target_id = taxonomy_term.tid');
      $query->addExpression('COUNT(*)', 'no_of_events');
      $query->fields('taxonomy_term', ['name']);
      $query->groupBy('taxonomy_term.name');
      $res = $query->execute()->fetchAll();
    }
    catch (\Exception $e) {
      $res = NULL;
    }
    return $res;
  }

}
