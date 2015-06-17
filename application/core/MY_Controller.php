<?php  if(!defined('BASEPATH')) EXIT('No direct script asscess allowed');

class MY_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function assign($key,$val) {
        $this->ci_smarty->assign($key,$val);
    }

    public function display($html) {
        $this->ci_smarty->display($html);
    }

}