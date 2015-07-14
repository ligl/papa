<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends AdminBase
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        $this->display();
    }

    public function test()
    {
        $data["title"] = "标题";
        $data["num"] = "123123";

        $this->assign('data', $data);

        $this->display("test.html");
    }
}
