<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller{

  public function __construct() {
    parent::__construct();

    $this->load->library('twig');

    $this->load->model('categories_model');
    $categories = $this->categories_model->getAll();
    
    $this->load->vars('categories', $categories);
  }

}
