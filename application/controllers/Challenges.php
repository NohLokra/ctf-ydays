<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Challenges extends MY_Controller{

  public function __construct() {
    parent::__construct();
  }

  function index($category, $id = null) {
    if ( $id === null ) {
      // Si on a pas d'id, on affiche une catégorie
      $this->_category($category);
    } else {
      // Sinon on affiche un challenge
      $this->_challenge($category, $id);
    }
  }

  private function _category($category) {
    $this->twig->display('challenges/category.twig', [
      "page" => $category
    ]);
  }

  private function _challenge($category, $challenge_id) {
    $this->twig->display('challenges/challenge.twig', [
      "page" => $category
    ]);
  }

}
