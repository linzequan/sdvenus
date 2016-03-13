<?php
/**
 * 公共模块——分类管理模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class common_upload_model extends MY_Model {

    private $table = 'common_upload';
    private $fields = 'id, url, create_time, create_user_id';

    public function __construct() {
        parent::__construct();
    }


    public function search($params, $order, $page) {
        $where = array();
        if(count($order)==0) {
            $order[] = ' id desc ';
        }
        $datas = $this->db->get_page($this->table, $this->fields, $where, $order, $page);
        $this->load->model('sys/user_model', 'user_model');
        $CI = &get_instance();
        $root_dir = $this->config->item('root_dir');
        foreach($datas['rows'] as $k=>$v) {
            if($userinfo=$CI->user_model->get_userinfo_by_id($v['create_user_id'])) {
                $datas['rows'][$k]['create_user_name'] = $userinfo['user_name'];
            } else {
                $datas['rows'][$k]['create_user_name'] = '';
            }
            // 创建时间
            $datas['rows'][$k]['create_time'] = $v['create_time']=='' ? '' : date('Y-m-d H:i:s', $v['create_time']);
            if(substr($v['url'], 0, 4)=='http') {
                $datas['rows'][$k]['show_photo'] = $v['url'];
            } else {
                // 图片地址
                $datas['rows'][$k]['url'] = 'http://' . $_SERVER["HTTP_HOST"] . '/www/res/photo/' . $v['url'];
                // 预览图片
                $datas['rows'][$k]['show_photo'] = $datas['rows'][$k]['url'];
            }
        }
        return $datas;
    }


    public function insert($info) {
        $data = array(
            'url'           => get_value($info, 'url'),
            'create_time'   => time(),
            'create_user_id'=> $this->session->userdata('user_id')
        );
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        return $id;
    }


    public function delete($id) {
        $this->db->delete($this->table, array('id'=>$id));
        return $this->create_result(true, 0, '删除成功');
    }
}
