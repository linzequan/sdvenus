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

        $start_time = get_value($params, 'start_time');         // 创建开始时间
        $end_time = get_value($params, 'end_time');             // 创建结束时间
        $name = get_value($params, 'name');                     // 标题

        $where = array();
        if($start_time!='') {
            $where[] = array('create_time', strtotime($start_time), '>=');
        }
        if($end_time!='') {
            $where[] = array('create_time', strtotime($end_time), '<=');
        }
        if($name!='') {
            $where[] = array('name', $name, 'like');
        }

        if(count($order)==0) {
            $order[] = ' id desc ';
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
            // 创建时间
            $datas['rows'][$k]['create_time'] = $v['create_time']=='' ? '' : date('Y-m-d H:i:s', $v['create_time']);
            // 更新时间
            $datas['rows'][$k]['update_time'] = $v['update_time']=='' ? '' : date('Y-m-d H:i:s', $v['update_time']);
        }
        return $datas;
    }


    public function get_info($id) {
        if($id<=0) {
            return array();
        }
        $result = array();

        $query = $this->db->select($this->fields)->where('id', $id)->get($this->table);
        if($query->num_rows()<=0) {
            return array();
        }
        $result = $query->row_array();
        return $result;
    }


    public function insert($info) {
        $data = array(
            'name'          => get_value($info, 'name'),
            'content'       => get_value($info, 'content'),
            'address'       => get_value($info, 'address'),
            'phone'         => get_value($info, 'phone'),
            'period'        => get_value($info, 'period'),
            'cover'         => get_value($info, 'cover'),
            'photoList'     => implode('####', get_value($info, 'photoList')),
            'rank'          => get_value($info, 'rank') ? get_value($info, 'rank') : 100,
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
                'photoList'     => implode('####', get_value($info, 'photoList')),
                'rank'          => get_value($info, 'rank') ? get_value($info, 'rank') : 100,
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


    public function get_detail($id) {
        if($id<=0) {
            return false;
        }
        $where = array('id'=>$id);
        $query = $this->db->get_where($this->table, $where);
        $rows = $query->result_array();
        if(count($rows)>0) {
            $str = '<table class="dv-table" border="0" style="width:100%;">' .
                        '<tr>' .
                            '<td class="dv-label">编号: </td>' .
                            '<td>' . $rows[0]['id'] . '</td>' .
                        '</tr>';
            if($rows[0]['name']!='') {
                $str .= '<tr>' .
                            '<td class="dv-label">标题: </td>' .
                            '<td>' . $rows[0]['name'] . '</td>' .
                        '</tr>';
            }
            if($rows[0]['zone_id']!='') {
                if($rows[0]['zone_id']=='-1') {
                    $zone_name = '不限';
                } else {
                    $this->load->model('sjyh_zone_model');
                    $CI = &get_instance();
                    $zoneinfo = $CI->sjyh_zone_model->getNameById($rows[0]['zone_id']);
                    $zone_name = $zoneinfo['name'];
                }
                $str .= '<tr>' .
                            '<td class="dv-label">地区: </td>' .
                            '<td>' . $zone_name . '</td>' .
                        '</tr>';
            }
            if($rows[0]['content']!='') {
                $str .= '<tr>' .
                            '<td class="dv-label">优惠内容: </td>' .
                            '<td>' . $rows[0]['content'] . '</td>' .
                        '</tr>';
            }
            if($rows[0]['address']!='') {
                $str .= '<tr>' .
                            '<td class="dv-label">地址: </td>' .
                            '<td>' . $rows[0]['address'] . '</td>' .
                        '</tr>';
            }
            if($rows[0]['phone']!='') {
                $str .= '<tr>' .
                            '<td class="dv-label">电话: </td>' .
                            '<td>' . $rows[0]['phone'] . '</td>' .
                        '</tr>';
            }
            if($rows[0]['period']!='') {
                $str .= '<tr>' .
                            '<td class="dv-label">优惠日期: </td>' .
                            '<td>' . $rows[0]['period'] . '</td>' .
                        '</tr>';
            }
            if($rows[0]['cover']!='') {
                $str .= '<tr>' .
                            '<td class="dv-label">封面图片: </td>' .
                            '<td><img src="' . $rows[0]['cover'] . '" style="height: 100px;"</td>' .
                        '</tr>';
            }
            if($rows[0]['photoList']!='') {
                $photoArr = explode('####', $rows[0]['photoList']);
                $photo = '';
                $i = 1;
                foreach($photoArr as $k=>$v) {
                    $next = ($i % 3 == 0) ? '<br/>' : '';
                    $photo .= '<img src="' . $v . '" style="height:100px; margin-right: 10px;">' . $next;
                    $i++;
                }
                $str .= '<tr>' .
                            '<td class="dv-label">正文图片: </td>' .
                            '<td>' . $photo . '</td>' .
                        '</tr>';
            }
            if($rows[0]['rank']!='') {
                $str .= '<tr>' .
                            '<td class="dv-label">排序: </td>' .
                            '<td>' . $rows[0]['rank'] . '</td>' .
                        '</tr>';
            }
            $str .= '</table>';
            return $str;
        } else {
            $str = '系统出错，刷新重试！';
            return $str;
        }
    }
}
