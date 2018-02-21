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

  public function checkPasswordBySlug($slug, $flag) {
    $challenge = $this->getBySlug($slug);

    return $this->authentication->check_passwd($challenge->hashed_password, $flag);
  }

  public function getAllToValidate() {
    return $this->search([
      "active" => 0,
      "removed" => 0
    ]);
  }

  public function removeInactive($id) {
    return $this->update($id, [
      "removed" => 1
    ]);
  }

  public function validate($id) {
    return $this->update($id, [
      "removed" => 0,
      "active" => 1
    ]);
  }

}
