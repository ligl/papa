<?php

/**
 * Created by PhpStorm.
 * User: amose
 * Date: 15/10/28
 * Time: 下午1:23
 */
class Ppkey extends AdminBase
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        echo 'welcome ppkey';
    }

    public function get()
    {
        $query = $this->db->query('select * from ppkey order by `limit` DESC limit 100');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $item) {
                $data[] = $item['code'];
            }
        }
        if ($data) {
            echo '<h3>' . implode('<br/>', $data) . '</h3>';
        } else {
            echo 'no data';
        }
    }

    public function build($count = 100, $limit = 3)
    {

        $count = intval($count);
        $limit = intval($limit);

        $ppkey_list = array();//目标ppkey list
        $ex_ppkey_list = array();//本次生成的ppkey在数据库中已存在的列表
        $is_complete = false;
        do {
            $ppkey = $this->get_rand_num(4);
            if (!in_array($ppkey, $ppkey_list) && !in_array($ppkey, $ex_ppkey_list)) {
                $ppkey_list[] = $ppkey;
            }
            if (count($ppkey_list) == $count) {
                $query = $this->db->query('select code from ppkey where code in (\'' . implode("','", $ppkey_list) . '\')');
                if ($query->num_rows() > 0) {
                    $rlt = $query->result_array();
                    $rlt_v = array();
                    foreach ($rlt as $key => $value) {
                        $rlt_v[] = $value['code'];
                    }
                    $ex_ppkey_list = array_merge($ex_ppkey_list, $rlt_v);
                    $ppkey_list = array_values(array_diff($ppkey_list, $ex_ppkey_list));
                } else {
                    $is_complete = true;
                    break;
                }

            }
        } while ($is_complete == false);


        $data = array();
        $reg_time = $this->millisecond();
        foreach ($ppkey_list as $ppkey) {
            $data[] = array('code' => $ppkey, 'limit' => $limit, 'reg_time' => $reg_time);
        }
        $rlt = $this->db->insert_batch('ppkey', $data);
        if ($rlt) {
            echo '[共' . count($ppkey_list) . '个] ' . implode(',', $ppkey_list);
        } else {
            echo '生成失败';
        }
    }

    /**
     * 获取随机数值
     *
     * @param int $len
     *
     * @return string
     */
    private function get_rand_num($len = 6)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        mt_srand((double)microtime() * 1000000 * getmypid());
        $code = "";
        while (strlen($code) < $len)
            $code .= substr($chars, (mt_rand() % strlen($chars)), 1);
        return $code;
    }
}