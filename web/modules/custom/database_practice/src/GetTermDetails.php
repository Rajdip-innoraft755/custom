<?php

namespace Drupal\database_practice;

use Drupal\mysql\Driver\Database\mysql\Connection;

/**
 * This service class is provide the term details.
 */
class GetTermDetails {

  /**
   * This is to store the database connection object.
   *
   * @var \Drupal\mysql\Driver\Database\mysql\Connection
   */
  protected Connection $connection;

  /**
   * This method is to construct the object of the GetTermDetails.
   *
   * It basically inilize the required services for this services.
   *
   * @param \Drupal\mysql\Driver\Database\mysql\Connection $connection
   *   The database connection object.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * This function is to fetch the details of a taxonomy term.
   *
   * Here, we run the database query to fetch the basic term details along with
   * the details of node which are related with that term, and pass the results
   * to makeResponse function to get the response string.
   *
   * @return string
   *   This returns the response string.
   */
  public function getDetails(string $name) {
    // This query fetch the term's basic details such as term name,id and uuid.
    $query = $this->connection->select('taxonomy_term_field_data', 'taxonomy_data');
    $query->join('taxonomy_term_data', 'taxonomy_detils', 'taxonomy_data.tid = taxonomy_detils.tid');
    $query->where('binary taxonomy_data.name = :name', [
      'name' => $name,
    ])
      ->fields('taxonomy_detils', ['uuid'])
      ->fields('taxonomy_data', ['tid', 'name']);
    $res = $query->execute()->fetchAll();

    // This query fetch the linked node's details of the specific term.
    $query = $this->connection->select('taxonomy_index', 'index')
      ->condition('index.tid', $res[0]->tid, '=');
    $query->join('node_field_data', 'node', 'index.nid = node.nid');
    $query->fields('node', ['nid', 'title']);
    $node_res = $query->execute()->fetchAll();

    return $this->makeResponse($res, $node_res);
  }

  /**
   * This is to design the response string to display.
   *
   * @param array $res
   *   The result array for basic term details.
   * @param array $node_res
   *   The result array fot the related node details.
   *
   * @return string
   *   Returns the response string.
   */
  public function makeResponse(array $res, array $node_res) {
    $response = '<b>';
    if ($res) {
      $response = $response . 'TERM NAME :' . $res[0]->name . '<br> <br>';
      $response = $response . 'UUID : ' . $res[0]->uuid . '<br> <br>';
      $response = $response . 'ID :' . $res[0]->tid . '<br> <br>';
      if ($node_res) {
        $response = $response . '-------------: THE LINKED NODES :------------- <br>';
        foreach ($node_res as $items) {
          $response = $response . $items->title . ' ' . '<a href="/node/' . $items->nid . '">Link</a><br>';
        }
      }
      else {
        $response = $response . 'NO NODE IS LINKED TILL NOW.';
      }
    }
    else {
      $response = $response . 'NO SUCH TERM FOUND';
    }
    return $response . '</b>';
  }

}
