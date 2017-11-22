<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Challenges extends MY_Controller{

  public function __construct() {
    parent::__construct();
  }

  function index($category, $id = null) {
    // Si on a pas d'id, on affiche une catégorie
    if ( $id === null ) {
      $this->twig->display('challenges/category.twig', [
        "page" => $category
      ]);
    } else {
      // Sinon on affiche un challenge
      $this->twig->display('challenges/challenge.twig', [
        "page" => $category
      ]);
    }
  }

}
