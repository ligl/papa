<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job extends ApiBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo 'job controller';
    }

    public function  update_torrent_weight()
    {
        echo 'update_story_weight:';
        $this->update_story_weight();
        echo '=====update_vido_weight:';
        $this->update_video_weight();
    }

    private function update_story_weight()
    {
        $maxWeight = $this->get_max_story_weight();
        for ($i = 0; $i < 20; $i++) {
            $sql = 'SELECT t1.* FROM `story` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `story` where `status`=0)-(SELECT MIN(id) FROM `story`  where `status`=0))+(SELECT MIN(id) FROM `story`  where `status`=0)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1;';
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0) {
                $rlt = $query->row_array();
                $sql = 'update story set weight=' . ($maxWeight + 1) . ' where id =' . $rlt['id'];
                $isSuccess = $this->db->simple_query($sql);
                echo $rlt['id'] . '-' . $isSuccess . ',';
            } else {
                //没有符合条件的数据
            }
        }
    }

    private function get_max_story_weight()
    {
        $sql = 'select max(weight) as weight from story';
        $query = $this->db->query($sql);
        $row = $query->row_array();
        $maxWeight = intval($row['weight']);
        return $maxWeight;
    }

    private function update_video_weight()
    {
        $maxWeight = $this->get_max_video_weight();
        for ($i = 0; $i < 20; $i++) {
            $sql = 'SELECT t1.* FROM `video` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `video` where `status`=0)-(SELECT MIN(id) FROM `video`  where `status`=0))+(SELECT MIN(id) FROM `video`  where `status`=0)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1;';
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0) {
                $rlt = $query->row_array();
                $sql = 'update video set weight=' . ($maxWeight + 1) . ' where id =' . $rlt['id'];
                $isSuccess = $this->db->simple_query($sql);
                echo $rlt['id'] . '-' . $isSuccess . ',';
            } else {
                //没有符合条件的数据
            }
        }
    }
    private function get_max_video_weight()
    {
        $sql = 'select max(weight) as weight from video';
        $query = $this->db->query($sql);
        $row = $query->row_array();
        $maxWeight = intval($row['weight']);
        return $maxWeight;
    }

}