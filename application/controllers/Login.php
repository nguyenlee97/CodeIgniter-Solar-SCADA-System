<?php
/**
 * Created by PhpStorm.
 * User: Ly Xuan Truong
 * Date: 06/11/2018
 * Time: 3:09 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/** 
 * @property global_function $global_function
 * @property M_login $M_login
 * @property Mglobal $Mglobal
 */
class Login extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('html');
        $this->load->helper('url');
        $this->load->Model("M_login");
        $this->load->library("global_function");
        if(!isset($_SESSION[LOGIN])){
            $_SESSION[LOGIN] = NULL;
        }

    }

    public function index(){
        if($_SESSION[LOGIN]!=NULL){
            header('location:'.base_url());
        }

        $username = $this->global_function->fixSql($this->input->post('usn'));
        $password = $this->global_function->fixSql($this->input->post('pas'));

        if($username=="" && $password==""){
            $this->load->view('login');
        }else{
            if($username!="" && $password !='' ){
                $user_info =$this->check_login($username,$password);
                if($user_info!=NULL){
                    // login true
                    // lấy danh sách các khu vưc được cấp quyền nếu là tài khoản phụ => chưa có phân quyền nên lấy tạm tất cả
                    if($user_info['loaiTaiKhoan']=='phu'){
                        $listQuyenIDKhuVuc = json_decode($user_info['permission'],true);
                    }else{
                        $listQuyenIDKhuVuc=array();
                    }
                    $_SESSION[LOGIN] = array(
                        'username' => $user_info['username'],
                        'name' => $user_info['name'],
                        'tenCongTy' => $user_info['tenCongTy'],
                        'loaiTaiKhoan' => $user_info['loaiTaiKhoan'],
                        'taiKhoanCapTren' => $user_info['taiKhoanCapTren'],
                        'listQuyenIDKhuVuc' => $listQuyenIDKhuVuc,
                    );

                    $data_login = array(
                        'state' => 'success',
                        'alert' => 'Welcome'
                    );
                }else{
                    $data_login = array(
                        'state' => 'error',
                        'alert' => 'Login false !',
                    );
                }
            }else{
                // login false
                $data_login = array(
                    'state' => 'error',
                    'alert' => 'Login false !',
                );
            }

            echo json_encode($data_login);
        }
    }

    public function logout(){
        $_SESSION[LOGIN] = NULL;
        header('location:'.base_url('login'));
    }

    private function check_login($username,$password){
        $password = $this->global_function->hash_password($password);
        $user_info = $this->M_login->get_login_user($username,$password);
        return $user_info;
    }


    public function change_password(){
        $data_view['template'] = "change_password";
        $data_view['title'] = "Thay đổi mật khẩu";
        $data_view['iconTitle'] = "cil-sync";
        $data_view['data'] = NULL;
        $data_view['data']['thongTinCty'] = $_SESSION[LOGIN]['tenCongTy'];


        $this->load->view('layout/layout',$data_view);
    }

    public function save_change_pass(){
        $username = $_SESSION[LOGIN]['username'];
        $old_pass = $this->global_function->fixSql($this->input->post('old_pass'));
        $new_pass = $this->global_function->fixSql($this->input->post('new_pass'));

        $check_old_pass = $this->check_login($username,$old_pass);

        if($check_old_pass==NULL){
            $result = array(
                'state' => 'error',
                'alert' => 'Mật khẩu không đúng !',
            );
        }else{
            $password_new = $this->global_function->hash_password($new_pass);
            $this->M_login->save_change_password($username,$password_new);
            $result = array(
                'state' => 'success',
                'alert' => 'Mật khẩu đã được thay đổi!',
            );
        }

        echo json_encode($result);
    }


}
