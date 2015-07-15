<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends HomeBase
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        //ads
        $cur_time = $this->millisecond();
        $query = $this->db->query('select * from ads where status=0 and expire_time>? order by weight desc',array($cur_time));
        if ($query->num_rows() > 0) {
            $ads = $query->result_array();
        } else {
            //TODO no
        }
        $this->assign('ads', $ads);

//        //story
//        $query=$this->db->query('select * from story where weight>=0 order by weight desc limit 20');
//        if ($query->num_rows() > 0) {
//            $storyList = $query->result_array();
//        } else {
//            //TODO no
//        }
//        $this->assign('storyList',$storyList);

        //video
//        $query=$this->db->query('select * from story where weight>=0 order by weight desc limit 20');
//        if ($query->num_rows() > 0) {
//            $videoList = $query->result_array();
//        } else {
//            //TODO no
//        }
//        $this->assign('videoList',$videoList);

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
