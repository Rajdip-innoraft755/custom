<?php

namespace Drupal\mymodule;

/**
 * This class is responsible to provide the services of validation of any type
 * of user data.
 *
 * @package Drupal\mymodule
 *
 * @author Rajdip Roy <rajdip.roy@innoraft.com>
 */
class ValidateData {
  /**
   * This function is responsible for validating the user input data like email,
   * phone number and full name and store the errors in an array and return
   * the array.
   *
   * @param array $values
   *   Stores the input values submitted by the user in the form as an array.
   *
   * @return array
   *   Returs the error array after validating all of the data.
   */
  public function validation(array $values)
  {
    $error = [];
    if (!$values['full_name']) {
      $error['full_name'] = 'Please enter the full name.';
    } elseif (!preg_match('/^[a-zA-Z ]*$/', $values['full_name'])) {
      $error['full_name'] = 'Full name should only contain alphabets.';
    }

    $domain_name = explode('.', explode('@', $values['email'])[1])[0];
    $extension = explode('.', explode('@', $values['email'])[1])[1];
    $supported_domain_name = ['gmail', 'yahoo', 'innoraft', 'outlook'];

    if (!$values['email']) {
      $error['email'] = 'Please enter the email ID.';
    } elseif (!in_array($domain_name, $supported_domain_name)) {
      $error['email'] = 'Email domain is not supported.';
    } elseif ($extension != 'com') {
      $error['email'] = 'Email extension is not supported.';
    }

    if (!$values['phone_no']) {
      $error['phone_no'] = 'Please enter the phone number.';
    } elseif (!preg_match('/^\+91[6-9][0-9]{9}$/', $values['phone_no'])) {
      $error['phone_no'] = 'Phone number is not a valid.';
    }
    return $error;
  }
}
