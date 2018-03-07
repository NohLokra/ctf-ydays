<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller{

  public function __construct() {
    parent::__construct();
  }

  function index() {

  }

  function login() {
		if( $this->uri->uri_string() == 'user/login')
			show_404();

		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' )
			$this->require_min_level(1);

		$this->setup_login_form();

    $this->twig->display('user/login_form.twig');
  }

  function logout() {
    $this->authentication->logout();

		// Set redirect protocol
		$redirect_protocol = USE_SSL ? 'https' : NULL;

		redirect( site_url( LOGIN_PAGE . '?' . AUTH_LOGOUT_PARAM . '=1', $redirect_protocol ) );
  }

  function profile() {
    $this->twig->display('messages/success.twig', [
      "message_intro" => "Profil",
      "message" => "Bienvenue sur votre profil",
      "page" => "profile"
    ]);
  }

  function register() {
    
  }

}
