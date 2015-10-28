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
        $this->display();
    }

    /**
     * video详页
     * @param $guid
     */
    public function video($guid = '')
    {
        if (empty($guid)) {
            //TODO random
            $query = $this->db->query('SELECT t1.* FROM `video` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `video` where `weight`>0)-(SELECT MIN(id) FROM `video`  where `weight`>0))+(SELECT MIN(id) FROM `video`  where `weight`>0)) AS id) AS t2 WHERE t1.id >= t2.id and weight>0 ORDER BY t1.id LIMIT 1;');
            if ($query->num_rows() > 0) {
                $video = $query->row_array();
            }
            //var_dump($video);
        } else {
            $query = $this->db->query('select * from video where guid=?', array($guid));
            if ($query->num_rows() > 0) {
                $video = $query->row_array();
            }
        }
        if (isset($video)) {
            if (empty($video['pics'])) {
                $video['pics'] = array();
            } else {
                $video['pics'] = explode(',', $video['pics']);
            }
            $this->assign('video', $video);
        }
        //var_dump($video);
        $this->assign('choice_url', base_url() . '/index/video/');
        $this->display();
    }

    /**
     * @param $hid ppkey_history id
     * @param $guid
     * @param $ppkey
     */
    public function down($hid, $guid, $ppkey)
    {
        $hid = intval($hid);
        $guid = $this->db->escape_str(trim($guid));
        $ppkey = $this->db->escape_str(trim($ppkey));

        //是否存在
        $query = $this->db->query("select * from ppkey_history where id=$hid and res_guid='$guid' and ppkey_code='$ppkey'");
        if ($query->num_rows() > 0) {
            $ppkey_history = $query->row_array();
        }
        if (!isset($ppkey_history)) {
            redirect(base_url(), 'refresh');
            exit;
        }
        //是否过期
        if ($this->millisecond() > $ppkey_history['expires']) {
            redirect(base_url(), 'refresh');
            exit;
        }
        $query = $this->db->query('select * from video where guid=?', array($guid));
        if ($query->num_rows() > 0) {
            $video = $query->row_array();
        }
        $this->assign('video', $video);
        $this->display();
    }
}
