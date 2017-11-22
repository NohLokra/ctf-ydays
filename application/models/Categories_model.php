<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories_model extends MY_Model {
  public function __construct() {
    parent::__construct("categories");
  }

  public function getBySlug($slug) {
    return $this->db->where('slug', $slug)->get($this->table)->row();
  }
}
