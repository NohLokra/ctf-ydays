<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller{

  public function __construct() {
    parent::__construct();
    
    $this->require_min_level(9);
  }

  function index() {

  }

  function manageUsers() {

  }
}
