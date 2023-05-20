<?php
/**
 * Created by PhpStorm.
 * User: Ly Xuan Truong
 * Date: 06/11/2018
 * Time: 3:09 PM
 */
class M_setting extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_list_location($taiKhoanKhachHang){
        $sql="SELECT * FROM khuvuclapdat WHERE state='1' and taiKhoanKhachHang='{$taiKhoanKhachHang}' ";
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function add_khuvuc($data){
        $sql="INSERT INTO khuvuclapdat (id,tenKhuVuc,mota,state,taiKhoanKhachHang,nguoiTao,create_time,chiTieuMax,chiTieuMin,donGia) 
            VALUES(
              '',
              '{$data['tenKhuVuc']}',
              '{$data['mota']}',
              '1',
              '{$data['taiKhoanKhachHang']}',
              '{$data['nguoiTao']}',
              STR_TO_DATE('".$data['ngay_tao']."', '%d/%m/%Y %H:%i:%s'),
              '{$data['chiTieuMax']}',
              '{$data['chiTieuMin']}',
              '{$data['donGia']}'
              ) ";

        $query = $this->db->simple_query($sql);
        return $query;
    }

    public function remove_khuvuc($id){
        // xoa data thiet bi
        $sql_dataTinHieu_tong="DELETE FROM `tinhieu_tong` WHERE idKhuVucLapDat='{$id}' ";
        $this->db->simple_query($sql_dataTinHieu_tong);

        // xoa data thiet bi log
        $sql_dataTinHieu_log="DELETE FROM `tinhieu_log` WHERE idKhuVucLapDat='{$id}' ";
        $this->db->simple_query($sql_dataTinHieu_log);

        // xoa data thiet bi string
        $sql_dataTinHieu_string="DELETE FROM `tinhieu_string` WHERE idThietBi in (
            SELECT idThietBi FROM `thietbi` WHERE idKhuVucLapDat='{$id}' 
        )
        ";
        $this->db->simple_query($sql_dataTinHieu_string);

        // xoa thiet bi log
        $sql_thietBiLog="DELETE FROM `thietbi_log` WHERE idThietBi in (
            SELECT idThietBi FROM `thietbi` WHERE idKhuVucLapDat='{$id}' 
        )
        ";
        $this->db->simple_query($sql_thietBiLog);
        
        // xoa thiet bi
        $sql_thietBi="DELETE FROM `thietbi` WHERE idKhuVucLapDat='{$id}' ";
        $this->db->simple_query($sql_thietBi);

        // xoa khu vuc
        $sql="DELETE FROM `khuvuclapdat` WHERE id='{$id}' ";
        $query = $this->db->simple_query($sql);

        return $query;
    }

    public function edit_khuvuc($data,$editTaiKhoanKhachHang=true){
        if($editTaiKhoanKhachHang==true){
            // table thietbi
            $sql="UPDATE thietbi 
            SET taiKhoanKhachHang='{$data['taiKhoanKhachHang']}' 
            WHERE idKhuVucLapDat ='{$data['id']}' ";
            $this->db->simple_query($sql);

            // table tinhieu_tong
            $sql="UPDATE tinhieu_tong 
            SET taiKhoanKhachHang='{$data['taiKhoanKhachHang']}' 
            WHERE idKhuVucLapDat ='{$data['id']}' ";
            $this->db->simple_query($sql);
        }

        $sql="UPDATE khuvuclapdat
        SET tenKhuVuc='{$data['tenKhuVuc']}',
            mota='{$data['mota']}',
            donGia='{$data['donGia']}',
            taiKhoanKhachHang='{$data['taiKhoanKhachHang']}'
        WHERE id='{$data['id']}' ";
        $query = $this->db->simple_query($sql);
        return $query;
    }

    public function thongTinKhuVuc($id){
        $sql="SELECT * FROM khuvuclapdat WHERE id='{$id}' limit 0,1 ";
        $query =$this->db->query($sql);
        $row= $query->row_array();
        return $row;
    }

//-------------------------------------------------------------------------

    public function get_list_device($taiKhoanKhachHang,$idKhuVuc){
        $sql="SELECT * FROM thietbi WHERE state='1' and taiKhoanKhachHang='{$taiKhoanKhachHang}' and idKhuVucLapDat='{$idKhuVuc}' ";
        // echo $sql;
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function thongTinThietBi($id){
        $sql="SELECT * FROM thietbi WHERE id='{$id}' limit 0,1 ";
        $query =$this->db->query($sql);
        $row= $query->row_array();
        return $row;
    }

    public function thongTinThietBiFromDeviceID($deviceID){
        $sql="SELECT * FROM thietbi WHERE idThietBi='{$deviceID}' limit 0,1 ";
        $query =$this->db->query($sql);
        $row= $query->row_array();
        return $row;
    }

    public function add_device($data){
        $sql="INSERT INTO thietbi (id,idThietBi,tenThietBi,mota,taiKhoanKhachHang,idKhuVucLapDat,state,nguoiTao,create_time) 
            VALUES(
              '',
              '{$data['idThietBi']}',
              '{$data['tenThietBi']}',
              '{$data['mota']}',
              '{$data['taiKhoanKhachHang']}',
              '{$data['idKhuVucLapDat']}',
              '1',
              '{$data['nguoi_tao']}',
              STR_TO_DATE('".$data['ngay_tao']."', '%d/%m/%Y %H:%i:%s')) ";
// echo $sql;
        $query = $this->db->simple_query($sql);
        return $query;
    }

    public function remove_thietbi($idThietBi){
        // xoa data thiet bi
        $sql_dataTinHieu_tong="DELETE FROM `tinhieu_tong` WHERE idThietBi ='{$idThietBi}' ";
        $this->db->simple_query($sql_dataTinHieu_tong);

        // xoa data thiet bi log
        $sql_dataTinHieu_log="DELETE FROM `tinhieu_log` WHERE idThietBi ='{$idThietBi}' ";
        $this->db->simple_query($sql_dataTinHieu_log);

        // xoa data thiet bi string
        $sql_dataTinHieu_string="DELETE FROM `tinhieu_string` WHERE idThietBi ='{$idThietBi}' ";
        $this->db->simple_query($sql_dataTinHieu_string);

        // xoa thiet bi log
        $sql_thietBiLog="DELETE FROM `thietbi_log` WHERE idThietBi ='{$idThietBi}' ";
        $this->db->simple_query($sql_thietBiLog);

        // xoa thiet bi
        $sql="DELETE FROM `thietbi` WHERE idThietBi='{$idThietBi}' ";
        $query = $this->db->simple_query($sql);
        return $query;
    }

    public function edit_thietbi($data_edit,$editTaiKhoanKhachHang,$editKhuVuc){
        if($editTaiKhoanKhachHang==true ){
            $sql="UPDATE tinhieu_tong SET 
            taiKhoanKhachHang='{$data_edit['taiKhoanKhachHang']}' 
            WHERE idThietBi='{$data_edit['idThietBi']}' ";
            $this->db->simple_query($sql);
        }

        if($editKhuVuc==true){
            $sql="UPDATE tinhieu_tong SET 
            idKhuVucLapDat='{$data_edit['idKhuVuc']}' 
            WHERE idThietBi='{$data_edit['idThietBi']}' ";
            $this->db->simple_query($sql);
        }


        $sql="UPDATE thietbi SET 
            tenThietBi='{$data_edit['tenThietBi']}',
            mota='{$data_edit['mota']}',
            idKhuVucLapDat='{$data_edit['idKhuVuc']}',
            taiKhoanKhachHang='{$data_edit['taiKhoanKhachHang']}' 
        WHERE idThietBi='{$data_edit['idThietBi']}' ";
        // echo $sql;
        $query = $this->db->simple_query($sql);
        return $query;
    }
}