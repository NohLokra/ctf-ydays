<?php

if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Twig
{
	private $CI;
	private $_twig;
	private $_template_dir;
	private $_cache_dir;

	/**
	 * Constructor
	 *
	 */
	function __construct($debug = false)
	{
		$this->CI =& get_instance();
		$this->CI->config->load('twig');

		log_message('debug', "Twig Autoloader Loaded");

		Twig_Autoloader::register();

		//HMVC patch by joseayram
		//$template_module_dir = APPPATH.'modules/'.$this->CI->router->fetch_module().'/views/';
		$template_global_dir= $this->CI->config->item('template_dir');
		$this->_template_dir = array($template_global_dir);

		//end HMVC patch


		$this->_cache_dir = $this->CI->config->item('cache_dir');

		$loader = new Twig_Loader_Filesystem($this->_template_dir);

		$this->_twig = new Twig_Environment($loader, array(
      'cache' => $this->_cache_dir,
    	'debug' => $debug,
		));

		$this->_initFilters();

    foreach(get_defined_functions() as $functions) {
  		foreach($functions as $function) {
    		$this->_twig->addFunction($function, new Twig_Function_Function($function));
  		}
  	}

    $this->_twig->registerUndefinedFunctionCallback(function($name) {
      if (function_exists($name)) {
        return new Twig_SimpleFunction($name, function() use($name) {
          return call_user_func_array($name, func_get_args());
        });
        return false;
      }
    });
	}

	public function add_function($name)
	{
		$this->_twig->addFunction($name, new Twig_Function_Function($name));
	}

	public function render($template, $data = array())
	{
    $data = array_merge($data, $this->CI->load->get_vars());

		$template = $this->_twig->loadTemplate($template);
		return $template->render($data);
	}

	public function display($template, $data = array())
	{
    $data = array_merge($data, $this->CI->load->get_vars());

		$template = $this->_twig->loadTemplate($template);
		$template->display($data);
	}

	public function registerUndefinedFunctionCallback($function) {
		$this->_twig->registerUndefinedFunctionCallback($function);
	}

	private function _initFilters() {
		//Ajoute les filtres relatifs à base64
		$this->_twig->addFilter(new Twig_SimpleFilter('to_base64', 'base64_encode'));
		$this->_twig->addFilter(new Twig_SimpleFilter('from_base64', 'base64_decode'));
	}
}
