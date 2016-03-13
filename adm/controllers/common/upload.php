<?php
/**
 * 公共模块——图片管理控制器
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class upload extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('common_upload_model', 'def_model');
    }


    public function index() {
        $this->load->view('common/upload');
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
            case 'delete':
                $id = $this->input->post('id');
                $result = $this->def_model->delete($id);
                break;
            case 'upload_file':
                if(!empty($_FILES)) {
                    $root_dir = $this->config->item('root_dir');
                    $fileParts = pathinfo($_FILES['uploadfile']['name']);
                    $targetFolder = $this->config->item('photo_dir');
                    $tempFile = $_FILES['uploadfile']['tmp_name'];
                    $targetPath = $root_dir . $targetFolder;
                    $fileName = time() . '.' . $fileParts['extension'];
                    $targetFile = rtrim($targetPath, '/') . '/' . $fileName;
                    $fileTypes = array('jpg', 'jpeg', 'gif', 'png');
                    if(in_array($fileParts['extension'], $fileTypes)) {
                        move_uploaded_file($tempFile, $targetFile);
                        $info = array(
                            'url'   => $fileName
                        );
                        if($this->def_model->insert($info)>0) {
                            $result = $this->create_result(true, 0, array('name'=>$fileName));
                        } else {
                            $result = $this->create_result(false, 0, array('msg'=>'图片上传成功，插入数据库出错！'));
                        }
                    } else {
                        $result = $this->create_result(false, 0, array('msg'=>'文件格式不正确'));
                    }
                }
                break;
        }
        $this->output_result($result);
    }
}
