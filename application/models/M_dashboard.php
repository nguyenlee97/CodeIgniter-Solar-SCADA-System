<?php
/**
 * Created by PhpStorm.
 * User: Ly Xuan Truong
 * Date: 06/11/2018
 * Time: 3:09 PM
 */
class M_dashboard extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // public function getData_monthInYear($year,$username,$idKhuVuc=NULL,$deviceID=NULL){
        
    //     if($idKhuVuc==""){
    //         $sql_khuvuc = "";
    //     }else{
    //         $sql_khuvuc=" and idKhuVucLapDat='{$idKhuVuc}' ";
    //     }

    //     if($deviceID==""){
    //         $sql_thietbi = "";
    //     }else{
    //         $sql_thietbi=" and idThietBi='{$deviceID}' ";
    //     }

    //     $sql="
    //     SELECT MONTH(c.ngayNhanTinHieu) thang,
    //             (SELECT MAX(PAC_Value) FROM `tinhieu_tong` p 
    //                 WHERE p.taiKhoanKhachHang='{$username}' ".$sql_khuvuc.$sql_thietbi."
    //                 and YEAR(p.ngayNhanTinHieu) ='{$year}'
    //                 and  MONTH(p.ngayNhanTinHieu) = MONTH(c.ngayNhanTinHieu)
    //                 group by MONTH(p.ngayNhanTinHieu) 
    //             ) PAC_Value 
    //         FROM `tinhieu_tong` c 
    //         WHERE 
    //         c.taiKhoanKhachHang='{$username}' ".$sql_khuvuc.$sql_thietbi."
    //         and YEAR(c.ngayNhanTinHieu) ='{$year}'
    //         group by MONTH(c.ngayNhanTinHieu) 
    //         order by MONTH(c.ngayNhanTinHieu)
    //     ";

    //     $query = $this->db->query($sql);
    //     $rs = $query->result_array();
    //     return $rs;
    // }

    public function getListYearHaveValue($username,$idKhuVuc=NULL,$deviceID=NULL){
        if($idKhuVuc==""){
            $sql_khuvuc = "";
        }else{
            $sql_khuvuc=" and idKhuVucLapDat='{$idKhuVuc}' ";
        }

        if($deviceID==""){
            $sql_thietbi = "";
        }else{
            $sql_thietbi=" and idThietBi='{$deviceID}' ";
        }

        $sql="SELECT YEAR(c.ngayNhanTinHieu) nam
            FROM `tinhieu_tong` c
            WHERE 
            c.taiKhoanKhachHang='{$username}' ".$sql_khuvuc.$sql_thietbi."
            group by YEAR(c.ngayNhanTinHieu) 
            order by YEAR(c.ngayNhanTinHieu)
        ";

        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function getSanLuongNgay($strNgay,$username,$idKhuVuc=NULL,$deviceID=NULL){
        
        if($idKhuVuc==""){
            $sql_khuvuc = "";
        }else{
            $sql_khuvuc=" and idKhuVucLapDat='{$idKhuVuc}' ";
        }

        if($deviceID==""){
            $sql_thietbi_p = "";
            $sql_thietbi_c = "";
        }else{
            $sql_thietbi_p=" and p.idThietBi='{$deviceID}' ";
            $sql_thietbi_c=" and c.idThietBi='{$deviceID}' ";
        }

        $sql="SELECT c.idThietBi,c.idKhuVucLapDat,c.DAY_ENERGY_Unit,c.DAY_ENERGY_Value,MONTH_ENERGY_Unit,MONTH_ENERGY_Value,YEAR_ENERGY_Unit,YEAR_ENERGY_Value 
            FROM  `tinhieu_tong` c
            WHERE                     
            c.taiKhoanKhachHang='{$username}' {$sql_khuvuc} {$sql_thietbi_c}
            and date(c.ngayNhanTinHieu)=date(STR_TO_DATE('{$strNgay}', '%d/%m/%Y'))
            order by c.idThietBi
        ";    
		//echo $sql; exit();
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function getSanLuongNgay_bk($strNgay,$username,$idKhuVuc=NULL,$deviceID=NULL){
        
        if($idKhuVuc==""){
            $sql_khuvuc = "";
        }else{
            $sql_khuvuc=" and idKhuVucLapDat='{$idKhuVuc}' ";
        }

        if($deviceID==""){
            $sql_thietbi_p = "";
            $sql_thietbi_c = "";
        }else{
            $sql_thietbi_p=" and p.idThietBi='{$deviceID}' ";
            $sql_thietbi_c=" and c.idThietBi='{$deviceID}' ";
        }

        $sql="SELECT c.idThietBi,c.idKhuVucLapDat,c.DAY_ENERGY_Unit,c.DAY_ENERGY_Value,MONTH_ENERGY_Unit,MONTH_ENERGY_Value,YEAR_ENERGY_Unit,YEAR_ENERGY_Value 
            FROM  `tinhieu_tong` c,
            (SELECT idThietBi,max(ngayNhanTinHieu) maxHgayNhanTinHieu FROM `tinhieu_tong` p 
                                WHERE 
                                p.taiKhoanKhachHang='{$username}' {$sql_khuvuc} {$sql_thietbi_p}
                                and date(p.ngayNhanTinHieu)=date(STR_TO_DATE('{$strNgay}', '%d/%m/%Y'))
                                group by idThietBi
            ) as tmp
            WHERE                     
            c.taiKhoanKhachHang='{$username}' {$sql_khuvuc} {$sql_thietbi_c}
            and date(c.ngayNhanTinHieu)=date(STR_TO_DATE('{$strNgay}', '%d/%m/%Y'))
            and c.idThietbi = tmp.idThietbi
            and c.ngayNhanTinHieu = tmp.maxHgayNhanTinHieu
            order by c.idThietBi
        ";
        
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function getdataTrongNgay($strNgay,$username,$idKhuVuc=NULL,$deviceID=NULL){
        
        if($idKhuVuc==""){
            $sql_khuvuc = "";
        }else{
            $sql_khuvuc=" and idKhuVucLapDat='{$idKhuVuc}' ";
        }

        if($deviceID==""){
            $sql_thietbi_p = "";
            $sql_thietbi_c = "";
        }else{
            $sql_thietbi_p=" and p.idThietBi='{$deviceID}' ";
            $sql_thietbi_c=" and c.idThietBi='{$deviceID}' ";
        }

        $sql="SELECT c.idThietBi,c.idKhuVucLapDat,c.duLieuTheoGioTomTat 
            FROM  `tinhieu_tong` c,
            (SELECT idThietBi,max(ngayNhanTinHieu) maxHgayNhanTinHieu FROM `tinhieu_tong` p 
                                WHERE 
                                p.taiKhoanKhachHang='{$username}' {$sql_khuvuc} {$sql_thietbi_p}
                                and date(p.ngayNhanTinHieu)=date(STR_TO_DATE('{$strNgay}', '%d/%m/%Y'))
                                group by idThietBi
            ) as tmp
            WHERE                     
            c.taiKhoanKhachHang='{$username}' {$sql_khuvuc} {$sql_thietbi_c}
            and date(c.ngayNhanTinHieu)=date(STR_TO_DATE('{$strNgay}', '%d/%m/%Y'))
            and c.idThietbi = tmp.idThietbi
            and c.ngayNhanTinHieu = tmp.maxHgayNhanTinHieu
            order by c.idThietBi
        ";
        
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function getSanLuongThang($thang,$nam,$username,$idKhuVuc=NULL,$deviceID=NULL){
        
        if($idKhuVuc==""){
            $sql_khuvuc = "";
        }else{
            $sql_khuvuc=" and idKhuVucLapDat='{$idKhuVuc}' ";
        }

        if($deviceID==""){
            $sql_thietbi_p = "";
            $sql_thietbi_c = "";
        }else{
            $sql_thietbi_p=" and p.idThietBi='{$deviceID}' ";
            $sql_thietbi_c=" and c.idThietBi='{$deviceID}' ";
        }

        $sql="SELECT c.idThietBi,c.idKhuVucLapDat,c.DAY_ENERGY_Unit,c.DAY_ENERGY_Value,MONTH_ENERGY_Unit,MONTH_ENERGY_Value,YEAR_ENERGY_Unit,YEAR_ENERGY_Value 
            FROM  `tinhieu_tong` c,
            (SELECT idThietBi,max(ngayNhanTinHieu) maxHgayNhanTinHieu FROM `tinhieu_tong` p 
                                WHERE 
                                p.taiKhoanKhachHang='{$username}' {$sql_khuvuc} {$sql_thietbi_p}
                                and YEAR(p.ngayNhanTinHieu)='{$nam}' and MONTH(p.ngayNhanTinHieu)='{$thang}'
                                group by idThietBi
            ) as tmp
            WHERE                     
            c.taiKhoanKhachHang='{$username}' {$sql_khuvuc} {$sql_thietbi_c}
            and YEAR(c.ngayNhanTinHieu)='{$nam}' and MONTH(c.ngayNhanTinHieu)='{$thang}'
            and c.idThietbi = tmp.idThietbi
            and c.ngayNhanTinHieu = tmp.maxHgayNhanTinHieu
            order by c.idThietBi
        ";
        
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function getSanLuongNam($nam,$username,$idKhuVuc=NULL,$deviceID=NULL){
        
        if($idKhuVuc==""){
            $sql_khuvuc = "";
        }else{
            $sql_khuvuc=" and idKhuVucLapDat='{$idKhuVuc}' ";
        }

        if($deviceID==""){
            $sql_thietbi_p = "";
            $sql_thietbi_c = "";
        }else{
            $sql_thietbi_p=" and p.idThietBi='{$deviceID}' ";
            $sql_thietbi_c=" and c.idThietBi='{$deviceID}' ";
        }

        $sql="SELECT c.idThietBi,c.idKhuVucLapDat,c.DAY_ENERGY_Unit,c.DAY_ENERGY_Value,MONTH_ENERGY_Unit,MONTH_ENERGY_Value,YEAR_ENERGY_Unit,YEAR_ENERGY_Value 
            FROM  `tinhieu_tong` c,
            (SELECT idThietBi,max(ngayNhanTinHieu) maxHgayNhanTinHieu FROM `tinhieu_tong` p 
                                WHERE 
                                p.taiKhoanKhachHang='{$username}' {$sql_khuvuc} {$sql_thietbi_p}
                                and YEAR(p.ngayNhanTinHieu)='{$nam}'
                                group by idThietBi
            ) as tmp
            WHERE                     
            c.taiKhoanKhachHang='{$username}' {$sql_khuvuc} {$sql_thietbi_c}
            and YEAR(c.ngayNhanTinHieu)='{$nam}' 
            and c.idThietbi = tmp.idThietbi
            and c.ngayNhanTinHieu = tmp.maxHgayNhanTinHieu
            order by c.idThietBi
        ";
        
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function getSanLuongTong($username,$idKhuVuc=NULL,$deviceID=NULL){
        
        if($idKhuVuc==""){
            $sql_khuvuc = "";
        }else{
            $sql_khuvuc=" and idKhuVucLapDat='{$idKhuVuc}' ";
        }

        if($deviceID==""){
            $sql_thietbi_p = "";
            $sql_thietbi_c = "";
        }else{
            $sql_thietbi_p=" and p.idThietBi='{$deviceID}' ";
            $sql_thietbi_c=" and c.idThietBi='{$deviceID}' ";
        }

        $sql="SELECT c.idThietBi,c.idKhuVucLapDat,c.TOTAL_ENERGY_Unit,c.TOTAL_ENERGY_Value 
            FROM  `tinhieu_tong` c,
            (SELECT idThietBi,max(ngayNhanTinHieu) maxHgayNhanTinHieu FROM `tinhieu_tong` p 
                                WHERE 
                                p.taiKhoanKhachHang='{$username}' {$sql_khuvuc} {$sql_thietbi_p} 
                                group by idThietBi
            ) as tmp
            WHERE                     
            c.taiKhoanKhachHang='{$username}' {$sql_khuvuc} {$sql_thietbi_c} 
            and c.idThietbi = tmp.idThietbi 
            and c.ngayNhanTinHieu = tmp.maxHgayNhanTinHieu 
            order by c.idThietBi
        ";
        
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }
    
    public function getDanhSachKhuVuc($taiKhoanChinh){
        $sql="SELECT id,tenKhuVuc,moTa,chiTieuMax,chiTieuMin,donGia FROM khuvuclapdat WHERE taiKhoanKhachHang='{$taiKhoanChinh}' and state=1 ORDER BY tenKhuVuc ";
       
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function getSoLuongThietBi($taiKhoanChinh,$idKhuVuc){
        if($idKhuVuc==""){
            $sql_khuvuc = "";
        }else{
            $sql_khuvuc=" and idKhuVucLapDat='{$idKhuVuc}' ";
        }
    
        $timeQuota = 10*60;
        $strTimeQuota = gmdate('d/m/Y H:i:s',time()+7*3600 - $timeQuota);
        $sql="SELECT count(*) as tong,
        (
            SELECT count(*) Online 
            FROM (
                SELECT idThietBi FROM `tinhieu_tong` p
                WHERE p.taiKhoanKhachHang='{$taiKhoanChinh}' {$sql_khuvuc}
                    and p.ngayNhanTinHieu > STR_TO_DATE('{$strTimeQuota}', '%d/%m/%Y %H:%i:%s')
                group by idThietBi
            ) listThietBiOnline
        ) Online
        FROM `thietbi` c WHERE c.taiKhoanKhachHang='{$taiKhoanChinh}' and c.state=1 {$sql_khuvuc} ";
        // echo $sql;
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row;
    }

    public function getPACCurrent($taiKhoanChinh,$idKhuVuc){
        if($idKhuVuc==""){
            $sql_khuvuc = "";
        }else{
            $sql_khuvuc=" and idKhuVucLapDat='{$idKhuVuc}' ";
        }

        $timeQuota = 5*60;
        $strTimeQuota = gmdate('d/m/Y H:i:s',time()+7*3600 - $timeQuota);
        $sql="SELECT c.idThietBi,c.PAC_Value,c.PAC_Unit 
            FROM  `tinhieu_tong` c,
            (SELECT idThietBi,max(p.ngayNhanTinHieu) maxHgayNhanTinHieu FROM `tinhieu_tong` p 
                                WHERE 
                                p.taiKhoanKhachHang='{$taiKhoanChinh}' {$sql_khuvuc}
                                and p.ngayNhanTinHieu > STR_TO_DATE('{$strTimeQuota}', '%d/%m/%Y %H:%i:%s')
                                group by idThietBi
            ) as tmp
            WHERE                     
            c.taiKhoanKhachHang='{$taiKhoanChinh}' {$sql_khuvuc}
            and c.ngayNhanTinHieu > STR_TO_DATE('{$strTimeQuota}', '%d/%m/%Y %H:%i:%s')
            and c.idThietbi = tmp.idThietbi
            and c.ngayNhanTinHieu = tmp.maxHgayNhanTinHieu
            order by c.idThietBi
        ";
        
        $query = $this->db->query($sql);
        $row = $query->result_array();
        return $row;
    }

    public function getDanhSachThietBiTheoKhuVuc($taiKhoanChinh,$idKhuVuc){
        $sql="SELECT * FROM thietbi WHERE taiKhoanKhachHang='{$taiKhoanChinh}' and idKhuVucLapDat='{$idKhuVuc}' and state=1 ORDER BY tenThietBi ";
       
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    // lấy dữ liệu sau cùng nếu như thiết bị online
    public function getInfoThietBiIfOnline($taiKhoanChinh,$idKhuVuc,$idThietBi){
        $timeQuota = 10*60;
        $strTimeQuota = gmdate('d/m/Y H:i:s',time()+7*3600 - $timeQuota);
        $sql="SELECT * FROM `tinhieu_tong` 
              WHERE taiKhoanKhachHang='{$taiKhoanChinh}' and idKhuVucLapDat='{$idKhuVuc}' and idThietBi='{$idThietBi}'
                and ngayNhanTinHieu > STR_TO_DATE('{$strTimeQuota}', '%d/%m/%Y %H:%i:%s') order by ngayNhanTinHieu desc limit 0,1 ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row;
    }

    // lấy dữ liệu sau cùng không phân biệt on hay offline
    public function getInfoThietBiLast($taiKhoanChinh,$idKhuVuc,$idThietBi){
        $sql="SELECT * FROM `tinhieu_tong` 
              WHERE taiKhoanKhachHang='{$taiKhoanChinh}' and idKhuVucLapDat='{$idKhuVuc}' and idThietBi='{$idThietBi}'
                order by ngayNhanTinHieu desc limit 0,1 ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row;
    }

    public function getThongTinThietBi($taiKhoanChinh,$idKhuVuc,$idThietBi){
        $sql="SELECT * FROM `thietbi` WHERE taiKhoanKhachHang='{$taiKhoanChinh}' and idKhuVucLapDat='{$idKhuVuc}' and idThietBi='{$idThietBi}' ";
        $query=$this->db->query($sql);
        $row =$query->row_array();
        return $row;
    }

    public function getThongTinKhuVuc($taiKhoanChinh,$idKhuVuc){
        $sql="SELECT * FROM `khuvuclapdat` WHERE taiKhoanKhachHang='{$taiKhoanChinh}' and id='{$idKhuVuc}'  ";
        $query=$this->db->query($sql);
        $row =$query->row_array();
        return $row;
    }

    public function getInfoString($idThietBi,$idTinHieu){
        $sql="SELECT * FROM `tinhieu_string` WHERE idThietBi='{$idThietBi}' and idTinHieu='{$idTinHieu}'  ORDER BY idString ";
        $query=$this->db->query($sql);
        $rs =$query->result_array();
        return $rs;
    }

    public function getInfoHead($idThietBi){
        $sql="SELECT * FROM `thietbi` WHERE idThietBi='{$idThietBi}' AND state='1' ORDER BY dateChangeHEAD DESC ";
        $query=$this->db->query($sql);
        $rs =$query->row_array();
        return $rs;
    }
    public function test($idThietBi){
        $ngayHienTai = gmdate('d/m/Y',time()+7*3600);
        $sql="SELECT duLieuTheoGio from tinhieu_tong c 
        WHERE idThietBi='{$idThietBi}'
        and date(ngayNhanTinHieu) = date(STR_TO_DATE('{$ngayHienTai}', '%d/%m/%Y'))";
        // echo $sql;
        $query=$this->db->query($sql);
        $row =$query->row_array();
        return $row['duLieuTheoGio'];
    }

    // -------------------------------------------------------------------------------------
    public function getPAC_khuVucDaChot($taiKhoanKhachHang,$idKhuVuc,$ngay){
        $sql="SELECT * FROM `pac_khuvuc` WHERE taiKhoanKhachhang='{$taiKhoanKhachHang}' and idKhuVucLapDat='{$idKhuVuc}' AND date(ngayNhanTinHieu) = date(STR_TO_DATE('{$ngay}', '%d/%m/%Y')) ORDER BY ngayNhanTinHieu";
        $query=$this->db->query($sql);
        $rs =$query->row_array();
        return $rs;
    }

    public function getPAC_thietBiTheoNgay($taiKhoanKhachHang,$idKhuVuc,$idThietBi,$ngay){
        $sql="SELECT duLieuTheoGioTomTat FROM `tinhieu_tong` 
        WHERE taiKhoanKhachhang='{$taiKhoanKhachHang}' 
        and idKhuVucLapDat='{$idKhuVuc}' and idThietBi='{$idThietBi}' AND date(ngayNhanTinHieu) = date(STR_TO_DATE('{$ngay}', '%d/%m/%Y')) ORDER BY ngayNhanTinHieu";
        $query=$this->db->query($sql);
        $rs =$query->row_array();
        return $rs;
    }

    public function getDS_idKhuVucQuanLy($taiKhoanChinh){
        $sql="SELECT id FROM khuvuclapdat WHERE state='1' and taiKhoanKhachHang='{$taiKhoanChinh}' ";
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function insertChoSoLieuPAC($taiKhoanKhachHang,$idKhuVucLapDat,$ngayNhanTinHieu,$dataChot){
        $sql = "INSERT INTO `pac_khuvuc`(
            id,
            taiKhoanKhachhang,
            idKhuVucLapDat,
            ngayNhanTinHieu,
            DATA
        ) 
        VALUES (
            '',
            '{$taiKhoanKhachHang}', 
            '{$idKhuVucLapDat}', 
            STR_TO_DATE('{$ngayNhanTinHieu}', '%d/%m/%Y %H:%i:%s'), 
            '{$dataChot}'
        ) ";
        $query = $this->db->query($sql);
        return $query;
    }
    // -------------------------------------------------------------------------------------
   
}


 

// select sum(TOTAL_ENERGY_Value)
// FROM
// (
// SELECT c.idThietBi,c.idKhuVucLapDat,c.TOTAL_ENERGY_Unit,c.TOTAL_ENERGY_Value 
//             FROM  `tinhieu_tong` c,
//             (SELECT idThietBi,max(ngayNhanTinHieu) maxHgayNhanTinHieu FROM `tinhieu_tong` p 
//                                 WHERE 
//                                 p.taiKhoanKhachHang='vutienducdaklak' and p.idKhuVucLapDat='1' 
//                                 group by idThietBi
//             ) as tmp
//             WHERE                     
//             c.taiKhoanKhachHang='vutienducdaklak' and c.idKhuVucLapDat='1' 
//             and c.idThietbi = tmp.idThietbi 
//             and c.ngayNhanTinHieu = tmp.maxHgayNhanTinHieu 
//             order by c.idThietBi
//     ) a

