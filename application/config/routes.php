<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['challenges/create'] = 'challenges/create';
$route['challenges/(:any)/(:any)'] = 'challenges/challenge/$1/$2';
$route['challenges/(:any)'] = 'challenges/category/$1';
$route[LOGIN_PAGE] = 'user/login';
$route['logout'] = 'user/logout';
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = true;
