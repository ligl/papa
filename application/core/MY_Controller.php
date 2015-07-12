<?php if (!defined('BASEPATH')) EXIT('No direct script asscess allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->ci_smarty->assign('base_url',base_url());
    }

    public function assign($key, $val)
    {
        $this->ci_smarty->assign($key, $val);
    }

    /**
     * 显示页面
     * @param null $html 模板名称
     */
    public function display($html = null)
    {
        if ($html == null) {
            $html = strtolower($this->router->fetch_class() . DIRECTORY_SEPARATOR . $this->router->fetch_method() . '.html');
        }
        $this->ci_smarty->display($html);
    }
    function millisecond()
    {
        return ceil(microtime(true) * 1000);
    }
}