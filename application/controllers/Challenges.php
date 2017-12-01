<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Challenges extends MY_Controller{

  public function __construct() {
    parent::__construct();
  }

  function category($category) {
    $category = $this->categories_model->getBySlug($category);

    $this->twig->display('challenges/category.twig', [
      "page" => $category->slug,
      "category" => $category,
      "title" => "Challenges de " . $category->label
    ]);
  }

  function challenge($category, $challenge_id) {
    $challenge = $this->challenges_model->get($challenge_id);

    $this->twig->display('challenges/challenge.twig', [
      "page" => $category,
      "challenge" => $challenge,
      "title" => $challenge->label
    ]);
  }
}
