<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends ApiBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo 'api controller';
    }

    /**
     * 随机获取一个种子
     * @param int $type 种子类型
     */
    public function dig()
    {
        //随机取类型
        $cat_arr = array(TORRENT_TYPE_STORY, TORRENT_TYPE_VIDEO);
        $type = $cat_arr[rand(0, count($cat_arr) - 1)];
        switch ($type) {
            case TORRENT_TYPE_VIDEO:
                $torrent = $this->rand_video_torrent();
                break;
            case TORRENT_TYPE_STORY:
                $torrent = $this->rand_story_torrent();
                break;
            case TORRENT_TYPE_PICTURE:
                //TODO waitting
                $sql = '';
                break;
        }
        if ($torrent) {
            echo json_encode(array('code' => 0, 'msg' => 'success', 'torrent' => $torrent));
        } else {
            echo json_encode(array('code' => 1, 'msg' => 'not found'));
        }
    }

    /**
     * 获取种子详细内容
     * @param $type 类型
     * @param $guid guid
     */
    public function  get_torrent_detail($type, $guid)
    {
        switch ($type) {
            case TORRENT_TYPE_VIDEO:
                $sql = "select * from video";
                break;
            case TORRENT_TYPE_STORY:
                $sql = "select * from story";
                break;
            case TORRENT_TYPE_PICTURE:
                //TODO waitting
                $sql = '';
                break;
            default:
                echo json_encode(array('code' => 1, 'msg' => 'not found'));
                exit;
        }
        if (!empty($guid)) {
            $sql .= ' where guid=?';
            $query = $this->db->query($sql, array($guid));
        } else {
            $query = $this->db->query($sql);
        }

        if ($query->num_rows() > 0) {
            $torrent = $query->row_array();
        } else {
            //没有符合条件的数据
            $torrent = array();
        }
        echo json_encode(array('code' => 0, 'msg' => 'success', 'torrent' => $torrent));
    }

    public function rand_story()
    {
        $sql = 'SELECT t1.* FROM `story` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `story` where `status`=0)-(SELECT MIN(id) FROM `story`  where `status`=0))+(SELECT MIN(id) FROM `story`  where `status`=0)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1;';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $torrent = $query->row_array();
        } else {
            //没有符合条件的数据
            $torrent = null;
        }
        echo json_encode(array('code' => 0, 'msg' => 'success', 'torrent' => $torrent));
    }

    private function rand_story_torrent()
    {
        $sql = 'SELECT t1.* FROM `story` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `story` where `status`=0)-(SELECT MIN(id) FROM `story`  where `status`=0))+(SELECT MIN(id) FROM `story`  where `status`=0)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1;';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $torrent['title'] = $row['title'];
            $torrent['cat_name']=$row['cat_name'];
            $torrent['url'] = base_url() . '/index/story/' . $row['guid'];
            $torrent['torrent_type'] = TORRENT_TYPE_STORY;
        } else {
            //没有符合条件的数据
            $torrent = null;
        }
        return $torrent;
    }

    private function rand_video_torrent()
    {
        $sql = 'SELECT t1.* FROM `video` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `video` where `status`=0)-(SELECT MIN(id) FROM `video`  where `status`=0))+(SELECT MIN(id) FROM `video`  where `status`=0)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1;';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $torrent['title'] = $row['title'];
            $torrent['url'] = base_url() . '/index/video/' . $row['guid'];
            $torrent['cat_name']=$row['cat_name'];
            $torrent['torrent_type'] = TORRENT_TYPE_VIDEO;
        } else {
            //没有符合条件的数据
            $torrent = null;
        }
        return $torrent;
    }
}