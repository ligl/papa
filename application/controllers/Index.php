<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
//        $ads = [
//            ['id' => 1, 'pic' => 'http://www.baidu.com/', 'title' => '#1', 'url' => '#1'],
//            ['id' => 2, 'pic' => 'http://www.baidu.com/', 'title' => '#2', 'url' => '#2'],
//            ['id' => 3, 'pic' => 'http://www.baidu.com/', 'title' => '#3', 'url' => '#3'],
//            ['id' => 4, 'pic' => 'http://www.baidu.com/', 'title' => '#4', 'url' => '#4'],
//            ['id' => 5, 'pic' => 'http://www.baidu.com/', 'title' => '#5', 'url' => '#5']
//        ];
        $cur_time = $this->millisecond();
        $query = $this->db->query('select * from ads where status=0 and expire_time>?',array($cur_time));
        if ($query->num_rows() > 0) {
            $ads = $query->result_array();
        } else {
            //TODO no
        }
        $this->assign('ads', $ads);
        $this->display();
    }

    /**
     * story详页
     * @param $guid
     */
    public function story($guid)
    {
        $query = $this->db->query('select * from story where guid=?', array($guid));
        if ($query->num_rows() > 0) {
            $story = $query->row_array();
        } else {
            //TODO no
        }
        $this->assign('story', $story);
        $this->display();
    }

    /**
     * video详页
     * @param $guid
     */
    public function video($guid)
    {
        $query = $this->db->query('select * from video where guid=?', array($guid));
        if ($query->num_rows() > 0) {
            $video = $query->row_array();
        } else {
            //TODO no
        }
        $this->assign('video', $video);
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
