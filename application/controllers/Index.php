<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
        //$this->load->database();
        //$rlt = $this->db->query("select * from story limit 5");
        //var_dump($rlt);
		$this->display();
        //echo $this->router->method;
	}

    public function test(){
        $data["title"]="标题";
        $data["num"]="123123";

        $this->assign('data',$data);

        $this->display("test.html");
    }
}
