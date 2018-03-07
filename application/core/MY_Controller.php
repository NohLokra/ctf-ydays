<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - MY Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2017, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

require_once APPPATH . 'third_party/community_auth/core/Auth_Controller.php';

class MY_Controller extends Auth_Controller{

  public function __construct() {
    parent::__construct();

    $this->load->library('twig');
    $this->load->helper("url");
    $this->load->helper("form");

    $this->load->model('categories_model');
    $categories = $this->categories_model->getAll();

    $this->load->model('challenges_model');

    if ( $this->is_logged_in() ) {
      $this->load->vars('is_logged_in', true);
    }

    $this->load->vars('categories', $categories);
  }

  protected function add_error($msg) {
    $loaded_vars = $this->load->get_vars();
    if ( isset($loaded_vars['errors']) ) {
      array_push($loaded_vars['errors'], $msg);
    } else {
      $loaded_vars['errors'] = [$msg];
    }

    $this->load->vars('errors', $loaded_vars['errors']);
  }

  protected function _get_missing_post_parameters($required_parameters) {
    $missing_params = [];

    foreach ( $required_parameters as $param => $desc ) {
      if ( !$this->input->post($param) ) array_push($missing_params, $desc);
    }

    return $missing_params;
  }

  protected function _check_mandatory_post_parameters($params) {
    foreach ( $params as $param ) {
      if ( !$this->input->post($param) ) {
        return false;
      }
    }

    return true;
  }

}
