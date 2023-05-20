<?php
/**
 * Created by PhpStorm.
 * User: Ly Xuan Truong
 * Date: 06/11/2018
 * Time: 3:09 PM
 */
class M_account extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_list_taikhoan($nhomTaiKhoan,$taikhoanchinh=NULL){
        switch($nhomTaiKhoan){
            case 'quantri':
                $sql="SELECT t.*,l.mota as tenLoaiTaiKhoan FROM taikhoan t,loaitaikhoan l WHERE state='1' and t.loaiTaiKhoan=l.id ";
            break;
            case 'nguoidung': default:
                $sql="SELECT t.*,l.mota as tenLoaiTaiKhoan FROM taikhoan t,loaitaikhoan l WHERE  state='1' and t.loaiTaiKhoan=l.id and (username='{$taikhoanchinh}' or taiKhoanCapTren='{$taikhoanchinh}')  ";
            break;
        }
        
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function get_tenLoaiTaiKhoan($loaiTaiKhoan){
        $sql="SELECT mota FROM loaitaikhoan WHERE id='{$loaiTaiKhoan}' ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row['mota'];
    }
    public function get_user_info($username,$onlyActive = true){
        if($onlyActive==true){
            $sql_active = " and state='1' ";
        }else{
            $sql_active = "";
        }
        $sql="SELECT * FROM taikhoan WHERE username='$username' {$sql_active} limit 0,1 ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row;
    }
    public function soLuongTaiKhoanPhu($taiKhoanCapTren){
        $sql="SELECT count(*) as tong FROM taikhoan WHERE taiKhoanCapTren='$taiKhoanCapTren' limit 0,1 ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row['tong'];
    }

    public function register($data){
        $sql="INSERT INTO taikhoan (
            username,password,name,state,tenCongTy,email,loaiTaiKhoan,taiKhoanCapTren,maxTaiKhoanPhu,permission,nguoiTao,create_time) 
            VALUES(
              '{$data['username']}',
              '{$data['password']}',
              '{$data['name']}',
              '1',
              '{$data['tenCongTy']}',
              '{$data['email']}',
              '{$data['loaiTaiKhoan']}',
              '{$data['taiKhoanCapTren']}',
              '{$data['maxTaiKhoanPhu']}',
              '{$data['permission']}',
              '{$data['nguoiTao']}',
              STR_TO_DATE('".$data['ngay_tao']."', '%d/%m/%Y %H:%i:%s')) ";
        // echo $sql;
        $query = $this->db->simple_query($sql);
        return $query;
    }

    public function get_list_taiKhoanCapTren(){
        $sql="SELECT username,name FROM taikhoan WHERE state='1' and loaiTaiKhoan='nguoidung' ";
        
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function remove_taikhoan($username){
        // xoa data tin hieu string
        $sql="DELETE FROM `tinhieu_string` WHERE idTinHieu in (
            SELECT id FROM tinhieu_tong WHERE taiKhoanKhachHang='{$username}'
        )";
        $this->db->simple_query($sql);

        // xoa data tin hieu 
        $sql="DELETE FROM `tinhieu_tong` WHERE taiKhoanKhachHang='{$username}'";
        $this->db->simple_query($sql);

        // xoa thiet bi log
        $sql_thietBiLog="DELETE FROM `thietbi_log` WHERE idThietBi in (
            SELECT idThietBi FROM `thietbi` WHERE taiKhoanKhachHang='{$username}' 
        )
        ";
        $this->db->simple_query($sql_thietBiLog);
        
        // xoa thiet bi
        $sql_thietBi="DELETE FROM `thietbi` WHERE taiKhoanKhachHang='{$username}'  ";
        $this->db->simple_query($sql_thietBi);

        // xoa khu vuc
        $sql="DELETE FROM `khuvuclapdat` WHERE taiKhoanKhachHang='{$username}' ";
        $this->db->simple_query($sql);

        // xoa tai khoan phu
        $user_info = $this->get_user_info($username,false);
        if($user_info['loaiTaiKhoan']=='nguoidung'){
            // xóa tài khoản phụ
            $sql="DELETE FROM taikhoan WHERE taiKhoanCapTren='{$username}'  ";
            $this->db->query($sql);
        }

        // xoa table taikhoan
        $sql="DELETE FROM taikhoan WHERE username='{$username}' ";
        $query = $this->db->query($sql);
        return $query;
        
    }
    
    public function editTaiKhoan($data_edit){
        $sql="UPDATE taikhoan SET 
            name='{$data_edit['name']}',
            password='{$data_edit['password']}',
            tenCongTy='{$data_edit['tenCongTy']}',
            email='{$data_edit['email']}',
            maxTaiKhoanPhu='{$data_edit['maxTaiKhoanPhu']}',
            permission='{$data_edit['permission']}'
            WHERE username='{$data_edit['username']}' ";
        $query = $this->db->simple_query($sql);
        return $query;
    }

    public function listKhuVucQuanLy($username){
        $sql="SELECT * FROM khuvuclapdat WHERE taiKhoanKhachHang='{$username}' ORDER BY tenKhuVuc ASC ";
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

}