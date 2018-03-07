<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller{

  public function __construct() {
    parent::__construct();
    
    $this->require_min_level(9);
  }

  function index() {
    $data["title"] = "Le Lieu des Tout-Puissants";
    
    $this->twig->display("admin/index.twig", $data);
  }

  function users() {
    $this->load->model("users_model");

    $data["users"] = $this->users_model->getAll();
    $data["title"] = "Gestion des users";

    $this->twig->display("admin/users.twig", $data);
  }

  function challenges() {
    $this->load->model("challenges_model");

    $data["challenges"] = $this->challenges_model->getAllToValidate();
    $data["title"] = "Gestion des challenges";

    $this->twig->display('admin/challenges.twig', $data);
  }
}
