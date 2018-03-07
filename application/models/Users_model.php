<?php
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Users_model extends MY_Model {
    
        function __construct() {
            parent::__construct("users", "user_id");
        }
    
    }
    
    /* End of file Users_model.php */
    