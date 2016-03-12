<?php
/**
 * 商家优惠——内容管理模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class sjyh_content_model extends MY_Model {

    private $table = 'sjyh_content';
    private $fields = 'id, name, content, address, phone, period, cover, zone_id, photoList, rank, create_time, create_user_id, update_time, update_user_id';

    public function __construct() {
        parent::__construct();
    }


    public function search($params, $order, $page) {
        $where = array();
        if(count($order)==0) {
            $order[] = ' time desc ';
        }
        $datas = $this->db->get_page($this->table, $this->fields, $where, $order, $page);
        $this->load->model('sys/user_model', 'user_model');
        $this->load->model('sjyh_zone_model');
        $CI = &get_instance();
        foreach($datas['rows'] as $k=>$v) {
            if($userinfo=$CI->user_model->get_userinfo_by_id($v['create_user_id'])) {
                $datas['rows'][$k]['create_user_name'] = $userinfo['user_name'];
            } else {
                $datas['rows'][$k]['create_user_name'] = '';
            }
            if($userinfo=$CI->user_model->get_userinfo_by_id($v['update_user_id'])) {
                $datas['rows'][$k]['update_user_name'] = $userinfo['user_name'];
            } else {
                $datas['rows'][$k]['update_user_name'] = '';
            }
            if($zoneinfo=$CI->sjyh_zone_model->getNameById($v['zone_id'])) {
                $datas['rows'][$k]['zone_name'] = $zoneinfo['name'];
            } else {
                $datas['rows'][$k]['zone_name'] = '';
            }
        }
        return $datas;
    }


    public function insert($info) {
        $data = array(
            'name'          => get_value($info, 'name'),
            'content'       => get_value($info, 'content'),
            'address'       => get_value($info, 'address'),
            'phone'         => get_value($info, 'phone'),
            'period'        => get_value($info, 'period'),
            'cover'         => get_value($info, 'cover'),
            'photoList'     => get_value($info, 'photoList'),
            'rank'          => get_value($info, 'rank'),
            'zone_id'       => get_value($info, 'zone_id'),
            'create_time'   => time(),
            'create_user_id'=> $this->session->userdata('user_id')
        );
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        return $this->create_result(true, 0, array('id'=>$id));
    }


    public function update($id, $info) {
        if($id==0) {
            $this->insert($info);
        } else {
            $data = array(
                'name'          => get_value($info, 'name'),
                'content'       => get_value($info, 'content'),
                'address'       => get_value($info, 'address'),
                'phone'         => get_value($info, 'phone'),
                'period'        => get_value($info, 'period'),
                'cover'         => get_value($info, 'cover'),
                'photoList'     => get_value($info, 'photoList'),
                'rank'          => get_value($info, 'rank'),
                'zone_id'       => get_value($info, 'zone_id'),
                'update_time'   => time(),
                'update_user_id'=> $this->session->userdata('user_id')
            );
            $where = array('id'=>$id);
            $this->db->update($this->table, $data, $where);
            return $this->create_result(true, 0, $where);
        }
    }


    public function delete($id) {
        $this->db->delete($this->table, array('id'=>$id));
        return $this->create_result(true, 0, '删除成功');
    }
}
