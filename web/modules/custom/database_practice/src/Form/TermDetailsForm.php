<?php

namespace Drupal\database_practice\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\database_practice\GetTermDetails;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This form is responsible for providing the taxonomy term details.
 *
 * There is one textfield to take the user's choice for the taxonomy term, based
 * on the input it shows the details of the term.
 */
class TermDetailsForm extends FormBase {

  /**
   * This is to store the object of GetTermDetails service class.
   *
   * @var \Drupal\database_practice\GetTermDetails
   */
  protected GetTermDetails $termDetails;

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database_practice.get_term_details'),
    );
  }

  /**
   * This method is to construct the object of the TermDetailsForm.
   *
   * It basically inilize the required services for this form.
   *
   * @param \Drupal\database_practice\GetTermDetails $term_details
   *   The object of GetTermDetails service class.
   */
  public function __construct(GetTermDetails $term_details) {
    $this->termDetails = $term_details;
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'taxonomy_term_details_form';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['term'] = [
      '#title' => $this->t('Enter the taxonomy term'),
      '#type' => 'textfield',
      '#description' => $this->t(
        'Enter the taxonomy term name to get the details.
        Please keep in mind that the name is case sensitive.'
      ),
      '#suffix' => '<p class="message error" id="user_id"></p>',
      '#size' => 10,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('GET THE DETAILS OF THIS TERM'),
      '#ajax' => [
        'callback' => '::getDetails',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Generating OTTL...'),
        ],
      ],
      '#suffix' => '<p class="message" id="details"><p>',
    ];

    return $form;
  }

  /**
   * This method is to provide response on ajax call.
   *
   * This function uses the GetTermDetails services. It passes the input value
   * to the specific service function and receives the result from that and
   * shows that result in the specific area of the page.
   *
   * @param array $form
   *   Takes the $form as argument.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Takes the FormStateInterface variable to retreive the input data.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Returns the AjaxResponse.
   */
  public function getDetails(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $res = $this->termDetails->getDetails($form_state->getValue('term'));
    $response->addCommand(new HtmlCommand('#details', $res));
    return $response;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
