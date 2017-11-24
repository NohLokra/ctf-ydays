<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories_model extends MY_Model {
  public function __construct() {
    parent::__construct("categories");
  }

  public function getBySlug($slug) {
    $this->load->model('challenges_model');
    $category = $this->db->where('slug', $slug)->get($this->table)->row();
    $category->challenges = $this->challenges_model->getForCategory($category->id);

    if ( !$category->challenges )
      $category->challenges = [];

    return $category;
  }
}
