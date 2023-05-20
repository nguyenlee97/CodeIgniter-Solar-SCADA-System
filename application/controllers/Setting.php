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
 * @property M_setting $M_setting
 * @property Mglobal $Mglobal
 */
class Setting extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('html');
        $this->load->helper('url');
        $this->load->Model("M_setting");
        $this->load->library("global_function");
        if(!isset($_SESSION[LOGIN])){
            $_SESSION[LOGIN] = NULL;
        }

        if($_SESSION[LOGIN]==NULL){
            header('location:'.base_url('login')); 
            exit();
        }
    }

    public function location(){
        $data_view['template'] = "setting/location";
        $data_view['title'] = "Khu vực lắp đặt";
        $data_view['iconTitle'] = "cil-compass";
        $data_view['data'] = NULL;
        $data_view['data']['thongTinCty'] = $_SESSION[LOGIN]['tenCongTy'];
        $data_view['data']['loaiTaiKhoan'] = $_SESSION[LOGIN]['loaiTaiKhoan'];
        
        $this->load->view('layout/layout',$data_view);
    }

    public function form_edit_location(){
        $data_view['data'] = NULL;
        $username = $_SESSION[LOGIN]['username'];
        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        $data_view['data']['thongTinCty'] = $_SESSION[LOGIN]['tenCongTy'];
        $data_view['data']['loaiTaiKhoan'] = $loaiTaiKhoan;

        $idKhuVuc = $this->global_function->fixSql($this->input->post('idKhuVuc'));
        // lay thong tin khu vuc
        $infoKhuVuc = $this->M_setting->thongTinKhuVuc($idKhuVuc);

        if($infoKhuVuc==NULL){
            $data_view['data']['allowEdit']=array(
                'state' => 'error',
                'alert' => "Không tìm thấy tài khoản cần sửa !"
            );
        }else{
            // kiểm tra xem tài khoản có quyền chỉnh sửa khu vực này không
            // kiểm tra xem có quyền edit tai khoan này hay ko?
            $data_view['data']['allowEdit']=array(
                'state' => 'success',
                'alert' => "Có thể sửa khu vực !"
            );
            switch($loaiTaiKhoan){
                case 'quantri':
                break;
                case 'nguoidung':
                    if($username != $infoKhuVuc['taiKhoanKhachHang']){
                        $data_view['data']['allowEdit']=array(
                            'state' => 'error',
                            'alert' => "Bạn không có quyền chỉnh sửa tài khoản này !"
                        );
                    }
                break;
                case 'phu':
                    $data_view['data']['allowEdit']=array(
                        'state' => 'error',
                        'alert' => "Bạn không có quyền chỉnh sửa tài khoản này !"
                    );
                break;
            }

            if($data_view['data']['allowEdit']['state']=='success'){
                $data_view['data']['infoKhuVuc'] = $infoKhuVuc;
            }
        }

        $this->load->view('setting/ajax/form_edit_location',$data_view);
    }

    public function list_location(){
        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        switch($loaiTaiKhoan){
            case 'quantri':
                $taiKhoanChinh=$this->global_function->fixSql($this->input->post('input_taikhoanKH'));
            break;
            case 'nguoidung':
                $taiKhoanChinh=$_SESSION[LOGIN]['username'];
            break;
            case 'phu': default:
                $taiKhoanChinh = $_SESSION[LOGIN]['taiKhoanCapTren'];
            break;
        }
        $data = $this->M_setting->get_list_location($taiKhoanChinh);
        $listLocation = array();
        if($data!=NULL){
            foreach($data as $index=>$item){
                if($loaiTaiKhoan=='phu' && !in_array($item['id'],$_SESSION[LOGIN]['listQuyenIDKhuVuc'])){
                    continue;
                }else{
                    $listLocation[] = array(
                        'stt' => $index+1,
                        'tenKhuVuc' => $item['tenKhuVuc'],
                        'mota' => $item['mota'],
                        'id' => $item['id'],
                    );
                }
                
            }
        }
        $result = array(
            'state' => 1,
            'alert' => "",
            'data' => $listLocation
        );
        echo json_encode($result);
        exit();
    }

    public function add_khuvuc(){
        $tenKhuVuc = $this->global_function->fixSql($this->input->post('tenKhuVuc'));
        $mota = $this->global_function->fixSql($this->input->post('mota'));
        $donGia = $this->global_function->fixSql($this->input->post('donGia'));
        
        if($_SESSION[LOGIN]['loaiTaiKhoan']=='quantri'){
            $taiKhoanKhachHang= $this->global_function->fixSql($this->input->post('taiKhoanCapTren'));
            if($taiKhoanKhachHang=="") {
                $result = array(
                    'state' => 'error',
                    'alert' => 'Vui lòng chọn tài khoản khách hàng cần thêm khu vực !',
                );
                echo json_encode($result);
                exit();
            }
        }
        else{
            $taiKhoanKhachHang = $_SESSION[LOGIN]['username'];
        }
        if($tenKhuVuc=="") {
            $result = array(
                'state' => 'error',
                'alert' => 'Vui lòng nhập tên khu vực !',
            );
            echo json_encode($result);
            exit();
        }

        $data_insert = array(
            'tenKhuVuc' => $tenKhuVuc,
            'mota' => $mota,
            'taiKhoanKhachHang' => $taiKhoanKhachHang,
            'ngay_tao' => gmdate('d/m/Y H:i:s',time()+7*3600),
            'nguoiTao' => $_SESSION[LOGIN]['username'],
            'chiTieuMax' => 1000,
            'chiTieuMin' => 0,
            'donGia' => $donGia,
        );

        $insert = $this->M_setting->add_khuvuc($data_insert);
        if($insert){
            $result = array(
                'state' => 'success',
                'alert' => 'Đã thêm khu vực lắp đặt !',
            );
        }else{
            $result = array(
                'state' => 'error',
                'alert' => 'Không thể thêm khu vực !',
            );
        }
        echo json_encode($result);
    }

    public function remove_khuvuc(){
        $username = $_SESSION[LOGIN]['username'];
        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        $id = $this->global_function->fixSql($this->input->post('id'));
        
        if($id==""){
            $result = array(
                'state' => 'error',
                'alert' => 'Không tìm thấy khu vực cần xóa !',
            );
            echo json_encode($result);
            exit();
        }

        $check = false;
        switch($loaiTaiKhoan){
            case 'quantri':
                $check = true;
            break;
            case 'nguoidung':
                // kiểm tra xem khu vực này của người dùng nào
                $thongTinKhuVuc = $this->M_setting->thongTinKhuVuc($id);
                if($thongTinKhuVuc['taiKhoanKhachHang']==$username){
                    $check = true;
                }else{
                    $result = array(
                        'state' => 'error',
                        'alert' => 'Bạn không có quyền xóa khu vực lắp đặt này !',
                    );
                    echo json_encode($result); exit();
                }
                
            break;
        }

        if($check){
            $remove = $this->M_setting->remove_khuvuc($id);
            if($remove){
                $result = array(
                    'state' => 'success',
                    'alert' => 'Đã xóa khu vực lắp đặt',
                );
            }else{
                $result = array(
                    'state' => 'error',
                    'alert' => 'Không thể xóa khu vực lắp đặt !',
                );
            }
        }
        
        echo json_encode($result);
        exit();
    }

    public function save_edit_khuvuc(){
        $username = $_SESSION[LOGIN]['username'];
        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        $idKhuVuc = $this->global_function->fixSql($this->input->post('idKhuVuc'));
        // lay thong tin khu vuc
        $infoKhuVuc = $this->M_setting->thongTinKhuVuc($idKhuVuc);
        if($infoKhuVuc==NULL){
            $result=array(
                'state' => 'error',
                'alert' => "Không tìm thấy khu vực cần sửa !"
            );
            echo json_encode($result); exit();
        }else{
            // kiểm tra xem tài khoản có quyền chỉnh sửa khu vực này không
            // kiểm tra xem có quyền edit tai khoan này hay ko?
            switch($loaiTaiKhoan){
                case 'quantri':
                break;
                case 'nguoidung':
                    if($username != $infoKhuVuc['taiKhoanKhachHang']){
                        $result=array(
                            'state' => 'error',
                            'alert' => "Bạn không có quyền chỉnh sửa tài khoản này !"
                        );
                        echo json_encode($result); exit();
                    }
                break;
                case 'phu':
                    $result=array(
                        'state' => 'error',
                        'alert' => "Bạn không có quyền chỉnh sửa tài khoản này !"
                    );
                    echo json_encode($result); exit();
                break;
            }
            
            if($loaiTaiKhoan=="quantri"){
                $taiKhoanKhachHang= $this->global_function->fixSql($this->input->post('taiKhoanCapTren'));
            }else{
                $taiKhoanKhachHang= $username;
            }
            $tenKhuVuc = $this->global_function->fixSql($this->input->post('tenKhuVuc'));
            $mota = $this->global_function->fixSql($this->input->post('mota'));
            $donGia = $this->global_function->fixSql($this->input->post('donGia'));

            if($infoKhuVuc['taiKhoanKhachHang']!=$taiKhoanKhachHang){
                $editTaiKhoanKhachHang = true;
            }else{
                $editTaiKhoanKhachHang = false;
            }

            $data_edit = array(
                'id' => $idKhuVuc,
                'tenKhuVuc' => $tenKhuVuc,
                'mota' => $mota,
                'donGia' => $donGia,
                'taiKhoanKhachHang' => $taiKhoanKhachHang,
                'ngay_tao' => gmdate('d/m/Y H:i:s',time()+7*3600),
                'nguoiTao' => $username,
            );

            $edit = $this->M_setting->edit_khuvuc($data_edit,$editTaiKhoanKhachHang);
            if($edit){
                $result = array(
                    'state' => 'success',
                    'alert' => 'Đã sửa thông tin khu vực lắp đặt !',
                );
            }else{
                $result = array(
                    'state' => 'error',
                    'alert' => 'Không thể sửa thông tin khu vực lắp đặt !',
                );
            }
            echo json_encode($result);
        }        
    }
    // ------------------------- DEVICE --------------------------------------
    public function device(){
        $data_view['template'] = "setting/device";
        $data_view['title'] = "Cài đặt thiết bị";
        $data_view['iconTitle'] = "cil-memory";
        $data_view['data'] = NULL;
        $data_view['data']['thongTinCty'] = $_SESSION[LOGIN]['tenCongTy'];
        $data_view['data']['loaiTaiKhoan'] = $_SESSION[LOGIN]['loaiTaiKhoan'];
        
        $this->load->view('layout/layout',$data_view);
    }

    public function form_edit_device(){
        $data_view['data'] = NULL;
        $username = $_SESSION[LOGIN]['username'];
        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        $data_view['data']['thongTinCty'] = $_SESSION[LOGIN]['tenCongTy'];
        $data_view['data']['loaiTaiKhoan'] = $loaiTaiKhoan;

        $idThietBi = $this->global_function->fixSql($this->input->post('idThietBi'));
        // lay thong tin thiết bị
        $infoThietBi = $this->M_setting->thongTinThietBiFromDeviceID($idThietBi);

        if($infoThietBi==NULL){
            $result=array(
                'state' => 'error',
                'alert' => "Không tìm thấy thiết bị cần sửa !"
            );
            echo json_encode($result); exit();
        }else{
            // kiểm tra xem tài khoản có quyền chỉnh sửa khu vực này không
            // kiểm tra xem có quyền edit tai khoan này hay ko?
            $data_view['data']['allowEdit']=array(
                'state' => 'success',
                'alert' => "Có thể sửa thông tin thiết bị !"
            );
            switch($loaiTaiKhoan){
                case 'quantri':
                break;
                case 'nguoidung':
                    if($username != $infoThietBi['taiKhoanKhachHang']){
                        $data_view['data']['allowEdit']=array(
                            'state' => 'error',
                            'alert' => "Bạn không có quyền chỉnh sửa thông tin thiết bị này !"
                        );
                    }
                break;
                case 'phu':
                    $data_view['data']['allowEdit']=array(
                        'state' => 'error',
                        'alert' => "Bạn không có quyền chỉnh sửa thông tin thiết bị này !"
                    );
                break;
            }

            if($data_view['data']['allowEdit']['state']=='success'){
                $data_view['data']['infoThietBi'] = $infoThietBi;
                $data_view['data']['infoThietBi']['tenKhuVuc']="";
                
            }
        }

        $this->load->view('setting/ajax/form_edit_device',$data_view);
    }

    public function list_device(){
        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        $idKhuVuc=$this->global_function->fixSql($this->input->post('input_idKhuVuc'));
        switch($loaiTaiKhoan){
            case 'quantri':
                $taiKhoanChinh=$this->global_function->fixSql($this->input->post('input_taikhoanKH'));
            break;
            case 'nguoidung':
                $taiKhoanChinh=$_SESSION[LOGIN]['username'];
            break;
            case 'phu': default:
                $taiKhoanChinh = $_SESSION[LOGIN]['taiKhoanCapTren'];
                if(!in_array($idKhuVuc,$_SESSION[LOGIN]['listQuyenIDKhuVuc'])){
                    $result = array(
                        'state' => 1,
                        'alert' => "",
                        'data' => array()
                    );
                    echo json_encode($result); exit();
                }
            break;
        }
        $data = $this->M_setting->get_list_device($taiKhoanChinh,$idKhuVuc);
        $listLocation = array();
        if($data!=NULL){
            foreach($data as $index=>$item){
                
                $listLocation[] = array(
                    'stt' => $index+1,
                    'idThietBi' => $item['idThietBi'],
                    'tenThietBi' => $item['tenThietBi'],
                    'id' => $item['id'],
                );
            }
        }
        $result = array(
            'state' => 1,
            'alert' => "",
            'data' => $listLocation
        );
        echo json_encode($result);
        exit();
    }

    public function add_device(){
        $deviceID = $this->global_function->fixSql($this->input->post('deviceID'));
        $tenThietBi = $this->global_function->fixSql($this->input->post('tenThietBi'));
        $mota = $this->global_function->fixSql($this->input->post('mota'));
        $khuvuc = $this->global_function->fixSql($this->input->post('khuvuc'));
        
        
        if($_SESSION[LOGIN]['loaiTaiKhoan']=='quantri'){
            $taiKhoanKhachHang= $this->global_function->fixSql($this->input->post('taiKhoanCapTren'));
            if($taiKhoanKhachHang=="") {
                $result = array(
                    'state' => 'error',
                    'alert' => 'Vui lòng chọn tài khoản khách hàng cần thêm khu vực !',
                );
                echo json_encode($result);
                exit();
            }            
        }
        else{
            $taiKhoanKhachHang = $_SESSION[LOGIN]['username'];
        }

        if($khuvuc=="") {
            $result = array(
                'state' => 'error',
                'alert' => 'Vui lòng chọn khu vực cần thêm thiết bị !',
            );
            echo json_encode($result);
            exit();
        }

        // kiểm tra xem khu vực có thuộc quản lý của tài khoản khách hàng không
        $info_khuvuc = $this->M_setting->thongTinKhuVuc($khuvuc);
        if($info_khuvuc['taiKhoanKhachHang']!=$taiKhoanKhachHang){
            $result = array(
                'state' => 'error',
                'alert' => 'Khu vực lắp đặt không thuộc quản lý của tài khoản !',
            );
            echo json_encode($result);
            exit();
        }

        if($tenThietBi=="") {
            $result = array(
                'state' => 'error',
                'alert' => 'Vui lòng nhập tên thiết bị !',
            );
            echo json_encode($result);
            exit();
        }

        // kiểm tra trùng device ID
        $device_info = $this->M_setting->thongTinThietBiFromDeviceID($deviceID);
        if($device_info==NULL){
            // có thể add
            $data_insert = array(
                'idThietBi' => $deviceID,
                'tenThietBi' => $tenThietBi,
                'mota' => $mota,
                'taiKhoanKhachHang' => $taiKhoanKhachHang,
                'idKhuVucLapDat' => $khuvuc,
                'ngay_tao' => gmdate('d/m/Y H:i:s',time()+7*3600),
                'nguoi_tao' => $_SESSION[LOGIN]['username'],
            );
            $insert = $this->M_setting->add_device($data_insert);
            if($insert){
                $result = array(
                    'state' => 'success',
                    'alert' => 'Cài đặt thiết bị thành công !',
                );
            }else{
                $result = array(
                    'state' => 'error',
                    'alert' => 'Không thể thêm thiết bị !',
                );
            }
        }else if($device_info['taiKhoanKhachHang']==$taiKhoanKhachHang){
                $result = array(
                    'state' => 'error',
                    'alert' => 'Thiết bị này đã được sử dụng !',
                );
        }else{
            $result = array(
                'state' => 'error',
                'alert' => 'Thiết bị đang được sử dụng bởi tài khoản khác!',
            );
        }
        echo json_encode($result);
        exit();
    }

    public function remove_device(){
        $username = $_SESSION[LOGIN]['username'];
        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        $id = $this->global_function->fixSql($this->input->post('id'));
        
        if($id==""){
            $result = array(
                'state' => 'error',
                'alert' => 'Không tìm thấy thiết bị cần xóa !',
            );
            echo json_encode($result);
            exit();
        }
        $thongTinThietBi = $this->M_setting->thongTinThietBi($id);
        $check = false;
        switch($loaiTaiKhoan){
            case 'quantri':
                $check = true;
            break;
            case 'nguoidung':
                // kiểm tra xem thiết bị này của người dùng nào
                if($thongTinThietBi['taiKhoanKhachHang']==$username){
                    $check = true;
                }else{
                    $result = array(
                        'state' => 'error',
                        'alert' => 'Bạn không có quyền xóa thiết bị này !',
                    );
                    echo json_encode($result); exit();
                }
                
            break;
        }

        if($check){
            $remove = $this->M_setting->remove_thietbi($thongTinThietBi['idThietBi']);
            if($remove){
                $result = array(
                    'state' => 'success',
                    'alert' => 'Đã xóa thiết bị thành công',
                );
            }else{
                $result = array(
                    'state' => 'error',
                    'alert' => 'Không thể xóa thiết bị !',
                );
            }
        }
        
        echo json_encode($result);
        exit();
    }

    public function save_edit_device(){
        $username = $_SESSION[LOGIN]['username'];
        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        $idThietBi = $this->global_function->fixSql($this->input->post('idThietBi'));
        // lay thong tin thiết bị
        $infoThietBi = $this->M_setting->thongTinThietBiFromDeviceID($idThietBi);

        if($infoThietBi==NULL){
            $result=array(
                'state' => 'error',
                'alert' => "Không tìm thấy khu vực cần sửa !"
            );
            echo json_encode($result); exit();
        }else{
            // kiểm tra xem tài khoản có quyền chỉnh sửa khu vực này không
            // kiểm tra xem có quyền edit tai khoan này hay ko?
            switch($loaiTaiKhoan){
                case 'quantri':
                break;
                case 'nguoidung':
                    if($username != $infoThietBi['taiKhoanKhachHang']){
                        $result=array(
                            'state' => 'error',
                            'alert' => "Bạn không có quyền chỉnh sửa thông tin thiết bị này !"
                        );
                        echo json_encode($result); exit();
                    }
                break;
                case 'phu':
                    $result=array(
                        'state' => 'error',
                        'alert' => "Bạn không có quyền chỉnh sửa thông tin thiết bị này !"
                    );
                    echo json_encode($result); exit();
                break;
            }

            if($loaiTaiKhoan=="quantri"){
                $taiKhoanKhachHang= $this->global_function->fixSql($this->input->post('taiKhoanCapTren'));
            }else{
                $taiKhoanKhachHang= $username;
            }
            $tenThietBi = $this->global_function->fixSql($this->input->post('tenThietBi'));
            $mota = $this->global_function->fixSql($this->input->post('mota'));

            $idKhuVuc = $this->global_function->fixSql($this->input->post('idKhuVuc'));
            // kiểm tra xem khu vực có thuộc quản lý của tài khoản không
            $infoKhuVuc = $this->M_setting->thongTinKhuVuc($idKhuVuc);
            
            if($infoKhuVuc['taiKhoanKhachHang']!=$taiKhoanKhachHang){
                $result=array(
                    'state' => 'error',
                    'alert' => "Khu vực lắp đặt và tài khoản không hợp lệ !"
                );
                echo json_encode($result); exit();
            }else{

                if($infoThietBi['taiKhoanKhachHang']!=$taiKhoanKhachHang){
                    $editTaiKhoanKhachHang = true;
                }else{
                    $editTaiKhoanKhachHang = false;
                }

                if($infoThietBi['idKhuVucLapDat']!=$idKhuVuc){
                    $editKhuVuc = true;
                }else{
                    $editKhuVuc = false;
                }

                // xử lý cập nhật
                $data_edit = array(
                    'idThietBi' => $idThietBi,
                    'tenThietBi' => $tenThietBi,
                    'mota' => $mota,
                    'taiKhoanKhachHang' => $taiKhoanKhachHang,
                    'idKhuVuc' => $idKhuVuc,
                    'ngay_tao' => gmdate('d/m/Y H:i:s',time()+7*3600),
                    'nguoiTao' => $username,
                );
    
                $edit = $this->M_setting->edit_thietbi($data_edit,$editTaiKhoanKhachHang,$editKhuVuc);
                if($edit){
                    $result = array(
                        'state' => 'success',
                        'alert' => 'Đã sửa thông tin thiết bị !',
                    );
                }else{
                    $result = array(
                        'state' => 'error',
                        'alert' => 'Không thể sửa thông tin thiết bị !',
                    );
                }
                echo json_encode($result);
            }
            
        }        
    }
}
