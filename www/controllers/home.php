<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class home extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }


    public function index() {
        header("Location: ".base_url()."adm");
    }
}
