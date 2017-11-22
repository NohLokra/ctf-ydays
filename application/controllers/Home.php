<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function index() {
		$this->load->library("twig");
		$this->twig->display("home/index.twig", [
      "title" => "Hi"
    ]);
	}
}
