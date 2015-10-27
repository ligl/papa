<?php
defined('BASEPATH') OR exit('No direct script access allowed');

error_reporting(E_ERROR);

class Api extends ApiBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo $this->input->ip_address();
        exit;
        echo 'api controller';
    }

    /**
     * 随机获取一个种子
     */
    public function dig()
    {
        $torrent = $this->rand_video_torrent();
        if ($torrent) {
            echo json_encode(array('code' => 0, 'msg' => 'success', 'torrent' => $torrent));
        } else {
            echo json_encode(array('code' => 1, 'msg' => 'not found'));
        }
    }

    public function get_video_list()
    {
        $page_size = 20;
        $page_index = intval($_GET['p']) * $page_size;

        $sql = "select * from video where weight>0 and poster!='' and pics!='' order by weight desc,id desc limit $page_index,$page_size";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $rlt = $query->result_array();
            foreach ($rlt as $key => $value) {
                $rlt[$key]['click_url'] = base_url() . '/index/video/' . $value['guid'];
            }
        }
        if (isset($rlt)) {
            echo json_encode(array('code' => 0, 'msg' => 'success', 'data' => $rlt));
        } else {
            echo json_encode(array('code' => 1, 'msg' => 'no data'));
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

    private function rand_video_torrent()
    {
        $sql = 'SELECT t1.* FROM `video` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `video` where `status`=0)-(SELECT MIN(id) FROM `video`  where `status`=0))+(SELECT MIN(id) FROM `video`  where `status`=0)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1;';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $torrent['title'] = $row['title'];
            $torrent['url'] = base_url() . '/index/video/' . $row['guid'];
            $torrent['cat_name'] = $row['cat_name'];
            $torrent['torrent_type'] = TORRENT_TYPE_VIDEO;
        } else {
            //没有符合条件的数据
            $torrent = null;
        }
        return $torrent;
    }

    /*
     * 获取下载的视频地址
     */
    public function trans()
    {
        $guid = trim($_POST['guid']);
        $ppkey = trim($_POST['ppkey']);
        $es_guid = $this->db->escape_str($guid);
        $es_ppkey = $this->db->escape_str($ppkey);

        //验证种子是否存在
        $sql = "SELECT * FROM `video` where weight>0 and guid='$es_guid'";
        $query = $this->db->query($sql);
        $video = $query->row_array();
        if (empty($video)) {
            echo json_encode(array('code' => 1, 'msg' => 'video not found'));
            exit;
        }
        //ppkey是否存在
        $sql = "select * from ppkey where code='$es_ppkey'";
        $query = $this->db->query($sql);
        $ppkey_obj = $query->row_array();
        if (empty($ppkey_obj)) {
            echo json_encode(array('code' => 2, 'msg' => 'ppkey not found'));
            exit;
        }
        $sql = "update ppkey set `limit`=`limit`-1 where `code`='$es_ppkey' and `limit`>0";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() == 0) {
            //不可使用
            echo json_encode(array('code' => 3, 'msg' => 'ppkey invalid'));
            exit;
        }
        //可以使用,插入历史
        $data = array('ppkey_code' => $es_ppkey, 'res_guid' => $es_guid, 'use_time' => $this->millisecond());
        $data['expires'] = $data['use_time'] + 30 * 60 * 1000;
        $data['ip'] = $this->input->ip_address();
        $this->db->insert("ppkey_history", $data);
        $id = $this->db->insert_id();
        if (!$id) {
            echo json_encode(array('code' => 4, 'msg' => 'history insert failed'));
            exit;
        }

        $rlt_data = array('down_url' => base_url() . "index/down/$id/$es_guid/$es_ppkey", 'ppkey_limit' => $ppkey_obj['limit'] - 1);
        echo json_encode(array('code' => 0, 'msg' => 'success', 'data' => $rlt_data));

    }
}