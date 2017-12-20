<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Challenges_model extends MY_Model{

  public function __construct() {
    parent::__construct("challenges", "id", ["file"]);
  }

  public function getForCategory($cat_id) {
    return $this->db->where('category_id', $cat_id)->get($this->table)->result();
  }

  public function getBySlug($slug) {
    return $this->search([
      "slug" => $slug
    ])[0];
  }

}
