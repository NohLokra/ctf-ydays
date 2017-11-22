<?php
defined('BASEPATH') OR exit('No direct script access allowed');
  include_once("DB_Model.php");

  class MY_Model extends DB_Model {

    public function __construct($table = "", $id_col = "id") {
      parent::__construct(ENVIRONMENT, $table, $id_col);
    }

  }
