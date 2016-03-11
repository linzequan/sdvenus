<?php
/**
 * 商家优惠——地区管理模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class sjyh_zone_model extends MY_Model {

    private $table = 'sjyh_zone';
    private $fields = 'id, name, rank, create_time, create_user_id, update_time, update_user_id';

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
        }
        return $datas;
    }


    public function insert($info) {
        $data = array(
            'name'          => get_value($info, 'name'),
            'rank'          => get_value($info, 'rank'),
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
                'rank'          => get_value($info, 'rank'),
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


    /**
     * 通过id获取名称
     * @param  integer $id 地区id
     * @return [type]      地区名称
     */
    public function getNameById($id=0) {
        $query = $this->db->get_where($this->table, array('id'=>$id));
        $result = $query->result_array();
        if(count($result)>0) {
            return $result[0];
        } else {
            return false;
        }
    }


    /**
     * 获取地区列表
     * @return [type] [description]
     */
    public function getList() {
        $result = $this->db->get_list($this->table, $this->fields);
        return $result['rows'];
    }
}
