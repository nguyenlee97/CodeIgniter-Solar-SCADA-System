<?php
/**
 * Created by PhpStorm.
 * User: Ly Xuan Truong
 * Date: 06/11/2018
 * Time: 3:09 PM
 */
class M_login extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function temp(){
        $sql="select * from [table_name] where 1=1 ";
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function get_login_user($username,$password){
        $sql="select * from taikhoan where username='$username' and password='{$password}' and state='1'  ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row;
    }

    public function get_user_info($username,$onlyActive = true){
        if($onlyActive==true){
            $sql_active = " and state='1' ";
        }else{
            $sql_active = "";
        }
        $sql="select * from taikhoan where username='$username' {$sql_active} limit 0,1 ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row;
    }

    public function save_change_password($username,$new_pass){
        $sql="UPDATE taikhoan set password='{$new_pass}' where username='{$username}' and state='1' ";
        $query = $this->db->simple_query($sql);
        return $query;
    }

    public function getListKhuVucOfKhachHang($username){
        $sql="SELECT id FROM `khuvuclapdat` WHERE taiKhoanKhachHang='{$username}' and state=1";
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }
}