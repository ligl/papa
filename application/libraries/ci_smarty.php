<?php
if(!defined('BASEPATH')) EXIT('No direct script asscess allowed');
require_once( APPPATH . 'libraries/smarty/Smarty.class.php' );

class Ci_smarty extends Smarty {
    protected $ci;
    public function  __construct(){
        parent::__construct();
        $this->ci = & get_instance();
        $this->ci->load->config('smarty');//加载smarty的配置文件 
        //获取相关的配置项 
        //// $this->template_dir= .. ;这是2.*的方法,3.1之后修改为 getXXX setXXX
        $this->setTemplateDir($this->ci->config->item('template_dir'));
        $this->setCompileDir($this->ci->config->item('compile_dir'));
        $this->setCacheDir($this->ci->config->item('cache_dir'));
        $this->setConfigDir($this->ci->config->item('config_dir'));
    }
} 