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
        //http://7xkhs6.com1.z0.glb.clouddn.com/123456.mp4 优衣库 试衣间
        //http://7xkbqi.com1.z0.glb.clouddn.com/yangmi.mp4 迷奸 酷似杨幂
        //ads
        $cur_time = $this->millisecond();
        $query = $this->db->query('select * from ads where status=0 and expire_time>? order by weight desc',array($cur_time));
        if ($query->num_rows() > 0) {
            $ads = $query->result_array();
        } else {
            //TODO no
        }
        $this->assign('ads', $ads);

        //top
        $topList = $this->getTop();
        $this->assign('topList',$topList);
        //story
        $query=$this->db->query('select *,2 as p_type from story where weight>=0 order by weight desc limit 20');
        if ($query->num_rows() > 0) {
            $storyList = $query->result_array();
        } else {
            //TODO no
        }
        //video
        $query=$this->db->query('select *,1 as p_type from video where weight>=0 order by weight desc limit 20');
        if ($query->num_rows() > 0) {
            $videoList = $query->result_array();
        } else {
            //TODO no
        }
        $resList = array_merge($storyList,$videoList);
        shuffle($resList);
        $this->assign('resList',$resList);
        $this->display();
    }

    private function getTop(){
        $query=$this->db->query('select id,title,cat_id,cat_name,content,guid,weight,2 as p_type from story where is_top=1 and weight>=0 union all select id,title,cat_id,cat_name,url as content,guid,weight,1 as p_type from video where is_top=1 and weight>=0');
        if ($query->num_rows() > 0) {
            $list = $query->result_array();
        } else {
            //TODO no
        }
        return $list;
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
