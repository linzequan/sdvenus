<?php
/**
 * 商家优惠——内容管理控制器
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class content extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('sjyh_content_model', 'def_model');
    }


    public function index() {
        $this->load->view('sjyh/content');
    }


    public function get() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
            case 'search':
                $params = $this->input->post('rs');
                $order = get_datagrid_order();
                $page = get_datagrid_page();
                $result = $this->def_model->search($params, $order, $page);
                break;
        }
        echo json_encode($result);
    }


    public function post() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
            case 'insert':
                $info = $this->get_request();
                $result = $this->def_model->insert($info);
                break;
            case 'update':
                $id = $this->input->post('id');
                $info = $this->get_request();
                $result = $this->def_model->update($id, $info);
                break;
            case 'delete':
                $id = $this->input->post('id');
                $result = $this->def_model->delete($id);
                break;
        }
        $this->output->result($result);
    }
}
