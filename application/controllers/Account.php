<?php
/**
 * Created by VSCode.
 * User: Ly Xuan Truong
 * Date: 28/07/2020
 * Time: 3:09 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/** 
 * @property global_function $global_function
 * @property M_account $M_account
 * @property Mglobal $Mglobal
 */
class Account extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('html');
        $this->load->helper('url');
        $this->load->Model("M_account");
        $this->load->library("global_function");
        if(!isset($_SESSION[LOGIN])){
            $_SESSION[LOGIN] = NULL;
        }

        if($_SESSION[LOGIN]==NULL){
            header('location:'.base_url('login')); exit();
        }
    }

    public function index(){
        $data_view['template'] = "account/account";
        $data_view['title'] = "Quản lý tài khoản";
        $data_view['iconTitle'] = "cil-people";
        $data_view['data'] = NULL;
        $data_view['data']['thongTinCty'] = $_SESSION[LOGIN]['tenCongTy'];

        $data_view['data']['ds_taiKhoanCapTren']=array();
        $data_view['data']['list_loaitaikhoan_create']=array();
        switch($_SESSION[LOGIN]['loaiTaiKhoan']){
            case 'quantri':
                $data_view['data']['ds_taiKhoanCapTren']=$this->M_account->get_list_taiKhoanCapTren();
                $data_view['data']['list_loaitaikhoan_create'][] = array(
                    'value' => 'quantri',
                    'text' => 'Tài khoản quản trị',
                );
                $data_view['data']['list_loaitaikhoan_create'][] = array(
                    'value' => 'nguoidung',
                    'text' => 'Tài khoản khách hàng',
                );
                $data_view['data']['list_loaitaikhoan_create'][] = array(
                    'value' => 'phu',
                    'text' => 'Tài khoản phụ',
                );
            break;
            case 'nguoidung':
                $data_view['data']['ds_taiKhoanCapTren'][] = array(
                    'username' => $_SESSION[LOGIN]['username'],
                    'name' => $_SESSION[LOGIN]['name'],
                ) ;
                $data_view['data']['list_loaitaikhoan_create'][] = array(
                    'value' => 'phu',
                    'text' => 'Tài khoản phụ',
                );
            break;
        }
        
        $this->load->view('layout/layout',$data_view);
    }

    public function list_taikhoan(){
        $username = $_SESSION[LOGIN]['username'];
        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        switch($loaiTaiKhoan){
            case 'quantri':
                $taikhoanchinh="";
            break;
            default:
                $taikhoanchinh = $username;
            break;
        }
        $data = $this->M_account->get_list_taikhoan($loaiTaiKhoan,$taikhoanchinh);
        $listTaiKhoan = array();
        if($data!=NULL){
            foreach($data as $index=>$item){
                $listTaiKhoan[] = array(
                    'stt' => $index+1,
                    'username' => $item['username'],
                    'name' => $item['name'],
                    'tenCongTy' => $item['tenCongTy'],
                    'email' => $item['email'],
                    'loaiTaiKhoan' => $item['loaiTaiKhoan'],
                    'tenLoaiTaiKhoan' => $item['loaiTaiKhoan']=='phu'? $item['tenLoaiTaiKhoan']." (".$item['taiKhoanCapTren'].")" : $item['tenLoaiTaiKhoan'] ,
                );
            }
        }
        $result = array(
            'state' => 1,
            'alert' => "",
            'data' => $listTaiKhoan
        );
        echo json_encode($result);
        exit();
    }

    public function list_taiKhoanCapTren(){
        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        $data =array();
        switch($loaiTaiKhoan){
            case 'quantri':
                $data=$this->M_account->get_list_taiKhoanCapTren();
            break;
            case 'nguoidung':
                $data[] = array(
                    'username' => $_SESSION[LOGIN]['username'],
                    'name' => $_SESSION[LOGIN]['name'],
                ) ;
            break;
        }
        $result = array(
            'state' => "success",
            'alert' => "",
            'data' => $data
        );
        echo json_encode($result);
        exit();
    }

    public function list_KhuVucQuanLy(){
        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        $data =array();
        switch($loaiTaiKhoan){
            case 'quantri':
                $taiKhoanChinh = $this->global_function->fixSql($this->input->post('taiKhoanCapTren'));
            break;
            case 'nguoidung':
                $taiKhoanChinh = $_SESSION[LOGIN]['username'];
            break;
        }
        $listKhuVuc = $this->M_account->listKhuVucQuanLy($taiKhoanChinh);
        $data=array();
        if($listKhuVuc!=NULL){
            foreach($listKhuVuc as $item){
                $data[] = array(
                    'idKhuVuc' => $item['id'],
                    'tenKhuVuc' => $item['tenKhuVuc'],
                    'mota' => $this->global_function->fixXSS($item['mota']),
                );
            }
        }
        
        $result = array(
            'state' => "success",
            'alert' => "",
            'data' => $data
        );
        echo json_encode($result);
        exit();

        
    }

    public function add_taikhoan(){
        $nguoiTao = $_SESSION[LOGIN]['username'];
        $name = $this->global_function->fixSql($this->input->post('name'));
        $username = $this->global_function->fixSql($this->input->post('username'));
        $pass = $this->global_function->fixSql($this->input->post('pass'));
        $tenCongTy = $this->global_function->fixSql($this->input->post('tenCongTy'));
        $email = $this->global_function->fixSql($this->input->post('email'));
        $loaiTaiKhoan = $this->global_function->fixSql($this->input->post('loaiTaiKhoan'));
        $maxTaiKhoanPhu = $this->global_function->fixSql($this->input->post('maxTaiKhoanPhu'));
        $taiKhoanCapTren = $this->global_function->fixSql($this->input->post('taiKhoanCapTren'));
        $ngay_tao=gmdate('d/m/Y H:i:s',time()+7*3600);
        if($username=="") {
            $result = array(
                'state' => 'error',
                'alert' => 'Vui lòng nhập Username !',
            );
            echo json_encode($result);
            exit();
        }

        if($name=="") {
            $result = array(
                'state' => 'error',
                'alert' => 'Vui lòng nhập tên người dùng !',
            );
            echo json_encode($result);
            exit();
        }

        if($pass=="") {
            $result = array(
                'state' => 'error',
                'alert' => 'Vui lòng nhập mật khẩu !',
            );
            echo json_encode($result);
            exit();
        }
        
        // nếu người dùng không phải quản lý thì tài khoản tạo ra chỉ có thể là tài khoản phụ và tài khoản cấp trên chính là tài khoản đang dùng
        if($_SESSION[LOGIN]['loaiTaiKhoan']!='quantri'){
            $loaiTaiKhoan='phu';
            $taiKhoanCapTren = $nguoiTao;
        } // ngược lại người dùng là quản lý thì tài khoản cấp trên và loại tài khoản sẽ lấy từ POST

        switch($loaiTaiKhoan){
            case 'phu':
                $maxTaiKhoanPhu=0;
                if($taiKhoanCapTren==""){
                    $result = array(
                        'state' => 'error',
                        'alert' => "Không thể xác định tài khoản phụ thuộc về tài khoản khách hàng nào",
                    );
                    echo json_encode($result); exit();
                }
            break;
            case 'quantri': case 'nguoidung':
                $taiKhoanCapTren="";
            break;
            
        }

        // kiểm tra trùng tài khoản
        $user_info = $this->M_account->get_user_info($username,false);
        if($user_info!=NULL){
            $result = array(
                'state' => 'error',
                'alert' => "Username ".$username." đã được sử dụng !",
            );
            echo json_encode($result); exit();
        }

        // kiểm tra max tài khoản phụ
        if($loaiTaiKhoan=='phu'){
            $user_info_taikhoanchinh = $this->M_account->get_user_info($taiKhoanCapTren,false);
            $max = $user_info_taikhoanchinh['maxTaiKhoanPhu'];

            // lấy số tài khoải phụ đã tạo
            $taiKhoanPhuDaTao = $this->M_account->soLuongTaiKhoanPhu($taiKhoanCapTren);
            if($taiKhoanPhuDaTao >= $max ){
                $result = array(
                    'state' => 'error',
                    'alert' => "Bạn đã sử dụng tối đa số lượng tài khoản được tạo !",
                );
                echo json_encode($result); exit();
            }
            $permission = json_encode($this->global_function->fixSql_arr($this->input->post('permission')));
        }else{
            $permission = "";
        }

        // xử lý tạo tài khoản
        if($user_info==NULL){
            // có thể add tài khoản
            $data_insert = array(
                'name' => $name,
                'username' => $username,
                'password' => $this->global_function->hash_password($pass),
                'tenCongTy' => $tenCongTy,
                'email' => $email,
                'loaiTaiKhoan' => $loaiTaiKhoan,
                'taiKhoanCapTren' => $taiKhoanCapTren,
                'maxTaiKhoanPhu' => $maxTaiKhoanPhu,
                'permission' => $permission,
                'nguoiTao' => $nguoiTao,
                'ngay_tao' => $ngay_tao,
            );
            $insert = $this->M_account->register($data_insert);
            if($insert){
                $result = array(
                    'state' => 'success',
                    'alert' => 'Tạo tài khoản người dùng thành công !',
                );
            }else{
                $result = array(
                    'state' => 'error',
                    'alert' => 'Không thể tạo tài khoản người dủng !',
                );
            }

        }
        echo json_encode($result);
    }

    public function remove_taikhoan(){
        $username = $_SESSION[LOGIN]['username'];
        $user_remove = $this->global_function->fixSql($this->input->post('username'));
        if($user_remove==""){
            $result = array(
                'state' => 'error',
                'alert' => 'Không tìm thấy tài khoản cần xóa !',
            );
            echo json_encode($result);
            exit();
        }

        if($user_remove==$username){
            $result = array(
                'state' => 'error',
                'alert' => 'Không thể xóa tài khoản đang sử dụng',
            );
            echo json_encode($result);
            exit();
        }

        $remove = $this->M_account->remove_taikhoan($user_remove);
        if($remove){
            $result = array(
                'state' => 'success',
                'alert' => 'Đã xóa tài khoản: '.$user_remove,
            );
        }else{
            $result = array(
                'state' => 'error',
                'alert' => 'Không thể xóa tài khoản: '.$user_remove.' !',
            );
        }

        echo json_encode($result);
        exit();
    }

    public function form_edit_account(){
        $data_view['data']=NULL;
        $user_edit = $this->global_function->fixSql($this->input->post('username'));
        
        $username = $_SESSION[LOGIN]['username'];
        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        $userInfo = $this->M_account->get_user_info($user_edit,false);
        if($userInfo==NULL){
            $data_view['data']['allowEdit']=array(
                'state' => 'error',
                'alert' => "Không tìm tháy tài khoản cần sửa !"
            );
        }else{
            // kiểm tra xem có quyền edit tai khoan này hay ko?
            $data_view['data']['allowEdit']=array(
                'state' => 'success',
                'alert' => "Có thể sửa tài khoản !"
            );
            switch($loaiTaiKhoan){
                case 'quantri':
                break;
                case 'nguoidung':
                    if($username != $userInfo['taiKhoanCapTren']){
                        $data_view['data']['allowEdit']=array(
                            'state' => 'error',
                            'alert' => "Bạn không có quyền chỉnh sửa tài khoản này !"
                        );
                    };
                break;
                case 'phu':
                    $data_view['data']['allowEdit']=array(
                        'state' => 'error',
                        'alert' => "Bạn không có quyền chỉnh sửa tài khoản này !"
                    );
                break;
            }
            
            if($data_view['data']['allowEdit']['state']=='success'){
                $data_view['data']['userInfo']=$userInfo;
                $data_view['data']['userInfo']['tenLoaiTaiKhoan'] = $this->M_account->get_tenLoaiTaiKhoan($userInfo['loaiTaiKhoan']);
    
                // lấy tên tài khoản cấp trên
                if($userInfo['loaiTaiKhoan']=="phu"){
                    $taiKhoanCapTren = $this->M_account->get_user_info($userInfo['taiKhoanCapTren']);
                    $data_view['data']['userInfo']['tenTaiKhoanCapTren'] = $taiKhoanCapTren['name'];
                }else{
                    $data_view['data']['userInfo']['tenTaiKhoanCapTren']="";
                }
    
                // lấy danh sách các khu vực của tài khoản cấp trên
                $data_view['data']['listKhuVuc']=array();
                if($userInfo['loaiTaiKhoan']=="phu"){
                    $arrPermission = $userInfo['permission']==NULL? array() : json_decode($userInfo['permission']);
                    $listKhuVuc = $this->M_account->listKhuVucQuanLy($userInfo['taiKhoanCapTren']);
                    if($listKhuVuc!=NULL){
                        foreach($listKhuVuc as $item){
                            if(in_array($item['id'],$arrPermission)){
                                $checked = "checked";
                            }else{
                                $checked = "";
                            }
                            $data_view['data']['listKhuVuc'][] = array(
                                'idKhuVuc' => $item['id'],
                                'tenKhuVuc' => $item['tenKhuVuc'],
                                'mota' => $this->global_function->fixXSS($item['mota']),
                                'checked' => $checked,
                            );
                        }
                    }
                }
            }
            
        }
        
        $this->load->view('account/ajax/form_edit',$data_view);
    }

    public function save_edit_taikhoan(){
        $user_edit = $this->global_function->fixSql($this->input->post('username'));
        $username = $_SESSION[LOGIN]['username'];
        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        $userInfo = $this->M_account->get_user_info($user_edit,false);
        if($userInfo==NULL){
            $result = array(
                'state' => 'error',
                'alert' => 'Không tìm tháy tài khoản cần sửa !',
            );
            echo json_encode($result);
            exit();
        }else{
            // kiểm tra xem có quyền edit tai khoan này hay ko?
            switch($loaiTaiKhoan){
                case 'quantri':
                break;
                case 'nguoidung':
                    if($username != $userInfo['taiKhoanCapTren']){
                        $result = array(
                            'state' => 'error',
                            'alert' => 'Bạn không có quyền chỉnh sửa tài khoản này !',
                        );
                        echo json_encode($result);
                        exit();
                    };
                break;
                case 'phu':
                    $result = array(
                        'state' => 'error',
                        'alert' => 'Bạn không có quyền chỉnh sửa tài khoản này !',
                    );
                    echo json_encode($result);
                    exit();
                break;
            }

            $name = $this->global_function->fixSql($this->input->post('name'));
            $pass = $this->global_function->fixSql($this->input->post('pass'));
            $tenCongTy = $this->global_function->fixSql($this->input->post('tenCongTy'));
            $email = $this->global_function->fixSql($this->input->post('email'));
            $loaiTaiKhoan = $this->global_function->fixSql($this->input->post('loaiTaiKhoan'));
            $maxTaiKhoanPhu = $this->global_function->fixSql($this->input->post('maxTaiKhoanPhu'));
            
            $ngay_tao=gmdate('d/m/Y H:i:s',time()+7*3600);

            if($name=="") {
                $result = array(
                    'state' => 'error',
                    'alert' => 'Vui lòng nhập tên người dùng !',
                );
                echo json_encode($result);
                exit();
            }
    
            if($pass=="") {
                $result = array(
                    'state' => 'error',
                    'alert' => 'Vui lòng nhập mật khẩu !',
                );
                echo json_encode($result);
                exit();
            }

            if($userInfo['password']!=$pass){
                $passwordNew=$this->global_function->hash_password($pass);
            }else{
                $passwordNew=$userInfo['password'];
            }

            if($userInfo['loaiTaiKhoan'] == 'phu'){
                $taiKhoanCapTren = $this->global_function->fixSql($this->input->post('taiKhoanCapTren'));
                $permission = json_encode($this->global_function->fixSql_arr($this->input->post('permission')));
            }else{
                $taiKhoanCapTren="";
                $permission="";
            }

            $data_edit = array(
                'name' => $name,
                'username' => $user_edit,
                'password' => $passwordNew,
                'tenCongTy' => $tenCongTy,
                'email' => $email,
                'loaiTaiKhoan' => $loaiTaiKhoan,
                'taiKhoanCapTren' => $taiKhoanCapTren,
                'maxTaiKhoanPhu' => $maxTaiKhoanPhu,
                'permission' => $permission,
                'nguoiTao' => $username,
                'ngay_tao' => $ngay_tao,
            );
            $edit = $this->M_account->editTaiKhoan($data_edit);
            if($edit){
                $result = array(
                    'state' => 'success',
                    'alert' => 'Sửa thông tin tài khoản thành công !',
                );
            }else{
                $result = array(
                    'state' => 'error',
                    'alert' => 'Không thể chỉnh sửa tài khoản !',
                );
            }
            echo json_encode($result);
        }

        
    }
    //--------------------------------------------------------

}
