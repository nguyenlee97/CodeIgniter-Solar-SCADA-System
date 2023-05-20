<?php
/**
 * Created by PhpStorm.
 * User: Ly Xuan Truong
 * Date: 06/11/2018
 * Time: 3:09 PM
 */
class M_api extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function temp(){
        $sql="SELECT * FROM [table_name] WHERE 1=1 ";
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function thongTinThietBiFromDeviceID($deviceID){
        $sql="SELECT * FROM thietbi WHERE idThietBi='{$deviceID}' and state='1' limit 0,1 ";
        $query =$this->db->query($sql);
        $row= $query->row_array();
        return $row;
    }

    public function capNhatHeadChoThietBi($deviceID,$HEAD,$Timestamp){
        $sql="UPDATE thietbi SET 
                HEAD='{$HEAD}', 
                dateChangeHEAD=STR_TO_DATE('{$Timestamp}', '%d/%m/%Y %H:%i:%s')  
              WHERE idThietBi='{$deviceID}' ";
        $query =$this->db->simple_query($sql);
        
        // Ghi log cho thiết bị
        $sql_log ="INSERT INTO thietbi_log (id,idThietBi,HEAD,dateChangeHEAD) 
                    VALUES(
                        '',
                        '{$deviceID}',
                        '{$HEAD}',
                        STR_TO_DATE('{$Timestamp}', '%d/%m/%Y %H:%i:%s')
                    )
                    ";
        $this->db->simple_query($sql_log);

        return $query;
    }


    public function ghiNhanTinHieuTong($deviceID,$taiKhoanKhachHang,$idKhuVucLapDat,$DATA,$duLieuTheoGio,$duLieuTheoGioTomTat,$Timestamp,$ngayTao){
        // ghi nhận tin hiệu tổng
        $sql="INSERT INTO tinhieu_tong (
            `id`, 
            `idThietBi`, 
            `taiKhoanKhachHang`, 
            `idKhuVucLapDat`, 
            `DAY_ENERGY_Unit`, 
            `DAY_ENERGY_Value`, 
            `MONTH_ENERGY_Unit`, 
            `MONTH_ENERGY_Value`, 
            `YEAR_ENERGY_Unit`, 
            `YEAR_ENERGY_Value`, 
            `TOTAL_ENERGY_Unit`, 
            `TOTAL_ENERGY_Value`, 
            `PAC_Unit`, 
            `PAC_Value`, 
            `FREQUENCY_AC_Unit`, 
            `FREQUENCY_AC`, 
            `INTERNAL_TEMP_Unit`, 
            `INTERNAL_TEMP_Value`, 
            `NUM_AC`, 
            `AC_A_UAC_Unit`, 
            `AC_A_UAC_Value`, 
            `AC_A_IAC_Unit`, 
            `AC_A_IAC_Value`, 
            `AC_B_UAC_Unit`, 
            `AC_B_UAC_Value`, 
            `AC_B_IAC_Unit`, 
            `AC_B_IAC_Value`, 
            `AC_C_UAC_Unit`, 
            `AC_C_UAC_Value`, 
            `AC_C_IAC_Unit`, 
            `AC_C_IAC_Value`, 
            `ngayNhanTinHieu`, 
            `duLieuTheoGioTomTat`, 
            `create_time`
        ) VALUES (
            '',
            '{$deviceID}', 
            '{$taiKhoanKhachHang}', 
            '{$idKhuVucLapDat}', 
            '{$DATA['DAY_ENERGY']['Unit']}', 
            '{$DATA['DAY_ENERGY']['Value']}', 
            '{$DATA['MONTH_ENERGY']['Unit']}', 
            '{$DATA['MONTH_ENERGY']['Value']}',  
            '{$DATA['YEAR_ENERGY']['Unit']}', 
            '{$DATA['YEAR_ENERGY']['Value']}', 
            '{$DATA['TOTAL_ENERGY']['Unit']}', 
            '{$DATA['TOTAL_ENERGY']['Value']}', 
            '{$DATA['PAC']['Unit']}', 
            '{$DATA['PAC']['Value']}',  
            '{$DATA['FREQUENCY_AC']['Unit']}', 
            '{$DATA['FREQUENCY_AC']['Value']}', 
            '{$DATA['INTERNAL_TEMP']['Unit']}', 
            '{$DATA['INTERNAL_TEMP']['Value']}', 
            '{$DATA['NUM_AC']}', 
            '{$DATA['AC_A']['UAC']['Unit']}', 
            '{$DATA['AC_A']['UAC']['Value']}', 
            '{$DATA['AC_A']['IAC']['Unit']}', 
            '{$DATA['AC_A']['IAC']['Value']}', 
            '{$DATA['AC_B']['UAC']['Unit']}', 
            '{$DATA['AC_B']['UAC']['Value']}', 
            '{$DATA['AC_B']['IAC']['Unit']}', 
            '{$DATA['AC_B']['IAC']['Value']}', 
            '{$DATA['AC_C']['UAC']['Unit']}', 
            '{$DATA['AC_C']['UAC']['Value']}', 
            '{$DATA['AC_C']['IAC']['Unit']}', 
            '{$DATA['AC_C']['IAC']['Value']}', 
            STR_TO_DATE('{$Timestamp}', '%d/%m/%Y %H:%i:%s'), 
            '{$duLieuTheoGioTomTat}', 
            STR_TO_DATE('{$ngayTao}', '%d/%m/%Y %H:%i:%s')
        )";
        $query =$this->db->query($sql);
        $id =$this->db->insert_id($sql);
        return $id;
    }

    public function ghiNhanLogTinHieuTong($deviceID,$taiKhoanKhachHang,$idKhuVucLapDat,$idTinHieuTong,$duLieuTheoGio,$Timestamp){
        // ghi nhan log
        $sql_log = "INSERT INTO tinhieu_log (
            `id`, 
            `idThietBi`, 
            `taiKhoanKhachHang`, 
            `idKhuVucLapDat`, 
            `ngayNhanTinHieu`, 
            `idTinHieuTong`, 
            `DATA`
            )
            VALUES(
                '',
                '{$deviceID}', 
                '{$taiKhoanKhachHang}', 
                '{$idKhuVucLapDat}', 
                STR_TO_DATE('{$Timestamp}', '%d/%m/%Y %H:%i:%s'), 
                '{$idTinHieuTong}',
                '{$duLieuTheoGio}'
            )
        ";
        $this->db->query($sql_log);
    }

    public function updateTinHieuTong($idTinHieu,$DATA,$duLieuTheoGio,$duLieuTheoGioTomTat,$Timestamp,$ngayTao){
       
        // ghi nhận tin hiệu tổng
        $sql="UPDATE tinhieu_tong SET
               DAY_ENERGY_Unit='{$DATA['DAY_ENERGY']['Unit']}', 
               DAY_ENERGY_Value='{$DATA['DAY_ENERGY']['Value']}', 
               MONTH_ENERGY_Unit='{$DATA['MONTH_ENERGY']['Unit']}', 
               MONTH_ENERGY_Value='{$DATA['MONTH_ENERGY']['Value']}',  
               YEAR_ENERGY_Unit='{$DATA['YEAR_ENERGY']['Unit']}', 
               YEAR_ENERGY_Value='{$DATA['YEAR_ENERGY']['Value']}', 
               TOTAL_ENERGY_Unit='{$DATA['TOTAL_ENERGY']['Unit']}', 
               TOTAL_ENERGY_Value='{$DATA['TOTAL_ENERGY']['Value']}', 
               PAC_Unit='{$DATA['PAC']['Unit']}', 
               PAC_Value='{$DATA['PAC']['Value']}',  
               FREQUENCY_AC_Unit='{$DATA['FREQUENCY_AC']['Unit']}', 
               FREQUENCY_AC='{$DATA['FREQUENCY_AC']['Value']}', 
               INTERNAL_TEMP_Unit='{$DATA['INTERNAL_TEMP']['Unit']}', 
               INTERNAL_TEMP_Value='{$DATA['INTERNAL_TEMP']['Value']}', 
               NUM_AC='{$DATA['NUM_AC']}', 
               AC_A_UAC_Unit='{$DATA['AC_A']['UAC']['Unit']}', 
               AC_A_UAC_Value='{$DATA['AC_A']['UAC']['Value']}', 
               AC_A_IAC_Unit='{$DATA['AC_A']['IAC']['Unit']}', 
               AC_A_IAC_Value='{$DATA['AC_A']['IAC']['Value']}', 
               AC_B_UAC_Unit='{$DATA['AC_B']['UAC']['Unit']}', 
               AC_B_UAC_Value='{$DATA['AC_B']['UAC']['Value']}', 
               AC_B_IAC_Unit='{$DATA['AC_B']['IAC']['Unit']}', 
               AC_B_IAC_Value='{$DATA['AC_B']['IAC']['Value']}', 
               AC_C_UAC_Unit='{$DATA['AC_C']['UAC']['Unit']}', 
               AC_C_UAC_Value='{$DATA['AC_C']['UAC']['Value']}', 
               AC_C_IAC_Unit='{$DATA['AC_C']['IAC']['Unit']}', 
               AC_C_IAC_Value='{$DATA['AC_C']['IAC']['Value']}', 
               ngayNhanTinHieu=STR_TO_DATE('{$Timestamp}', '%d/%m/%Y %H:%i:%s'),
               duLieuTheoGioTomTat='{$duLieuTheoGioTomTat}'
            WHERE id='{$idTinHieu}' 
        ";
 
        $query =$this->db->query($sql);

        // ghi nhan log
        $sql_log = "UPDATE tinhieu_log SET
            DATA='{$duLieuTheoGio}', ngayNhanTinHieu=STR_TO_DATE('{$Timestamp}', '%d/%m/%Y %H:%i:%s')
        WHERE
            idTinHieuTong='{$idTinHieu}' 
        ";
        $this->db->query($sql_log);

        return $query;
    }

    public function ghiNhanTinHieuString($deviceID,$idTinHieu,$dataString,$Timestamp,$ngayTao){
        $sql="INSERT INTO `tinhieu_string`(
            `id`, 
            `idThietBi`, 
            `idTinHieu`, 
            `idString`, 
            `PV_UDC_Unit`, 
            `PV_UDC_Value`, 
            `PV_IDC_Unit`, 
            `PV_IDC_Value`, 
            `ngayNhanTinHieu`, 
            `create_time`
            ) VALUES (
                '', 
                '{$deviceID}', 
                '{$idTinHieu}', 
                '{$dataString['ID_STRING']}', 
                '{$dataString['PV_UDC']['Unit']}', 
                '{$dataString['PV_UDC']['Value']}', 
                '{$dataString['PV_IDC']['Unit']}', 
                '{$dataString['PV_IDC']['Value']}', 
                STR_TO_DATE('{$Timestamp}', '%d/%m/%Y %H:%i:%s'), 
                STR_TO_DATE('{$ngayTao}', '%d/%m/%Y %H:%i:%s')
            ) 
        ";
        $this->db->simple_query($sql);
    }

    public function layTinHieuTheoNgay($deviceID,$taiKhoanKhachHang,$Timestamp){
        $strNgay = substr($Timestamp,0,10);
        $sql="SELECT id,duLieuTheoGio,duLieuTheoGioTomTat FROM `tinhieu_tong` 
            WHERE taiKhoanKhachHang='{$taiKhoanKhachHang}' and idThietBi='{$deviceID}' 
            and date(ngayNhanTinHieu)=date(STR_TO_DATE('{$strNgay}', '%d/%m/%Y'))
        ";
        $query =$this->db->query($sql);
        $row= $query->row_array();
        return $row;
    }

    public function layLogTinHieuTong($deviceID,$taiKhoanKhachHang,$idKhuVucLapDat,$idTinHieuTong){
        $sql="SELECT * FROM `tinhieu_log` 
            WHERE taiKhoanKhachHang='{$taiKhoanKhachHang}' and idThietBi='{$deviceID}'  and idKhuVucLapDat='{$idKhuVucLapDat}' and idTinHieuTong='{$idTinHieuTong}'
        ";
        $query =$this->db->query($sql);
        $row= $query->row_array();
        return $row;
    }

    public function xoaStringTheoIdTinHieu($deviceID,$idTinHieu){
        $sql="DELETE FROM `tinhieu_string` WHERE idThietBi='{$deviceID}' and idTinHieu='{$idTinHieu}' ";
        $query = $this->db->simple_query($sql);
        return $query;
    }

    public function layDuLieuCuoiCungCuaNgayTruoc($taiKhoanKhachHang,$idKhuVucLapDat,$idThietBi,$year,$month,$strNgay){
        $sql="SELECT MONTH_ENERGY_Unit,MONTH_ENERGY_Value
        FROM `tinhieu_tong` where taiKhoanKhachHang='{$taiKhoanKhachHang}' and idKhuVucLapDat='{$idKhuVucLapDat}' and idThietBi='{$idThietBi}' 
        and YEAR(ngayNhanTinHieu)='{$year}' 
        and MONTH(ngayNhanTinHieu)='{$month}' 
        and date(ngayNhanTinHieu) < date(STR_TO_DATE('{$strNgay}', '%d/%m/%Y'))
        ORDER BY ngayNhanTinHieu DESC limit 0,1 ";

        $query =$this->db->query($sql);
        $row= $query->row_array();
        return $row;
        
    }

    public function layDuLieuCuoiCungCuaNgayTruoc_khongXetThang($taiKhoanKhachHang,$idKhuVucLapDat,$idThietBi,$year,$strNgay){
        $sql="SELECT YEAR_ENERGY_Unit,YEAR_ENERGY_Value 
        FROM `tinhieu_tong` where taiKhoanKhachHang='{$taiKhoanKhachHang}' and idKhuVucLapDat='{$idKhuVucLapDat}' and idThietBi='{$idThietBi}' 
        and YEAR(ngayNhanTinHieu)='{$year}' 
        and date(ngayNhanTinHieu) < date(STR_TO_DATE('{$strNgay}', '%d/%m/%Y'))
        ORDER BY ngayNhanTinHieu DESC limit 0,1";
        // echo $sql;
        $query =$this->db->query($sql);
        $row= $query->row_array();
        return $row;
    }
    
    public function layDuLieuCuoiCung($taiKhoanKhachHang,$idKhuVucLapDat,$idThietBi){
        $sql="SELECT YEAR_ENERGY_Unit,YEAR_ENERGY_Value,MONTH_ENERGY_Unit,MONTH_ENERGY_Value,DAY_ENERGY_Unit,DAY_ENERGY_Value,TOTAL_ENERGY_Unit,TOTAL_ENERGY_Value
        FROM `tinhieu_tong` where taiKhoanKhachHang='{$taiKhoanKhachHang}' and idKhuVucLapDat='{$idKhuVucLapDat}' and idThietBi='{$idThietBi}' 
        ORDER BY ngayNhanTinHieu DESC limit 0,1";
        // echo $sql;
        $query =$this->db->query($sql);
        $row= $query->row_array();
        return $row;
    }

    // nâng cao hiệu năng

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

    public function thongTinPAC_DaChot($taiKhoanKhachHang,$idKhuVucLapDat,$ngayNhanTinHieu){
        $sql="SELECT * FROM `pac_khuvuc` 
        WHERE taiKhoanKhachHang='{$taiKhoanKhachHang}' 
        AND idKhuVucLapDat='{$idKhuVucLapDat}' 
        AND date(ngayNhanTinHieu)=date(STR_TO_DATE('{$ngayNhanTinHieu}', '%d/%m/%Y')) 
        ORDER BY ngayNhanTinHieu DESC
        ";

        $query = $this->db->query($sql);
        $rs = $query->row_array();
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

    public function updateChoSoLieuPAC($lastID,$ngayNhanTinHieu,$dataChot){
        $sql="UPDATE `pac_khuvuc` SET 
            ngayNhanTinHieu=STR_TO_DATE('{$ngayNhanTinHieu}', '%d/%m/%Y %H:%i:%s'),
            DATA='{$dataChot}'
            WHERE id = '{$lastID}'
        ";
        $query = $this->db->query($sql);
        return $query;
    }

}
//SELECT * FROM `tinhieu_tong` WHERE date(ngayNhanTinHieu) = STR_TO_DATE('08/08/2020','%d/%m/%Y %H:%i:%s')