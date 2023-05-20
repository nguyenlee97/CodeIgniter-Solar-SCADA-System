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
class Export extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('html');
        $this->load->helper('url');
        $this->load->Model("M_account");
        $this->load->library("global_function");
        $this->load->Model("M_dashboard");
        
        $this->load->database();
        if(!isset($_SESSION[LOGIN])){
            $_SESSION[LOGIN] = NULL;
        }

        if($_SESSION[LOGIN]==NULL){
            header('location:'.base_url('login')); exit();
        }
        if( $_SESSION[LOGIN]['loaiTaiKhoan'] != "quantri"){
            header('location:'.base_url()); exit();
        }
    }
    public function index(){
      
        $data_view['template'] = "export/data";
        $data_view['title'] = "Kết xuất dữ liệu";
        $data_view['breadcrumbLinkTitle'] = base_url('Dashboard/khuvuc/'.$this->uri->segment(3)."/".$this->uri->segment(4));

        $data_view['iconTitle'] = "cil-compass";
        $data_view['subTitle'] = "Export";
        $data_view['iconSubTitle'] = "cil-notes";

        $data_view['data'] = NULL;

        $data_view['data']['thongTinCty'] = $_SESSION[LOGIN]['tenCongTy'];
        $data_view['data']['loaiTaiKhoan'] = $_SESSION[LOGIN]['loaiTaiKhoan'];

        $data_view['data']['taiKhoanChinhSelected'] = $this->uri->segment(3);
        $data_view['data']['idKhuVucSelected'] = $this->uri->segment(4);
        $data_view['data']['idThietBiSelected'] = $this->uri->segment(5);
        //var_dump( $data_view);exit(0);
        $this->load->view('layout/layout',$data_view);
    }
    private function get_total_data_report( $customer_id,$area_id, $device_id,$date_from,$date_to){
        $query_select = "SELECT ngayNhanTinHieu, idThietBi, DAY_ENERGY_Unit, DAY_ENERGY_Value, PAC_Unit, PAC_Value, AC_A_UAC_Unit, AC_A_UAC_Value, AC_A_IAC_Unit, AC_A_IAC_Value, AC_B_UAC_Unit, AC_B_UAC_Value, AC_B_IAC_Unit, AC_B_IAC_Value, AC_C_UAC_Unit, AC_C_UAC_Value, AC_C_IAC_Unit, AC_C_IAC_Value FROM `tinhieu_tong` ";
        $where_sql ="WHERE `taiKhoanKhachHang`='". $customer_id."' and `idKhuVucLapDat`=". $area_id;
        if( $device_id != "all"){
            $where_sql = $where_sql." and `idThietBi` ='".$device_id."'";
        }
        $where_sql = $where_sql. " and `ngayNhanTinHieu` BETWEEN '".$date_from."' and '". $date_to."' order by `idThietBi`";
        $query_select =  $query_select. $where_sql;
      
        $query = $this->db->query( $query_select);
        $result = $query->result_array();
        return $result;

    }

    private function get_report_device_by_date($customer_id,$area_id, $device_id,$day){
        $dataReturn = array();
        $data= array();    
        $option ='hour';      
        switch($option){
            case 'hour': default:
                $ghiChuThoiGian = $day;
                if($device_id!="all"){
                    // lấy PAC của 1 thiết bị
                    $dataPAC=$this->M_dashboard->getPAC_thietBiTheoNgay($customer_id,$area_id, $device_id,$day);
                               
                    $dataPAC = json_decode($dataPAC['duLieuTheoGioTomTat'],true);
                    if($dataPAC!=NULL){
                        foreach($dataPAC as $item){
                            if(strtoupper($item['DATA']['PAC']['Unit'])=='W'){
                                $value = $item['DATA']['PAC']['Value']/1000;
                            }else{
                                $value = $item['DATA']['PAC']['Value'];
                            } 
                            $label = substr($item['Timestamp'],11,5);
                            $data[] = array(
                                            'time'=> $label,
                                            'pac'=> $value                                           
                            );
                        }
                    }
                }else {       
                   
                        // lấy PAC 1 khu vực                       
                        $dataPAC=$this->M_dashboard->getPAC_khuVucDaChot($customer_id,$area_id,$day);
                        if($dataPAC != NULL){
                            $dataPAC = json_decode($dataPAC['DATA'],true);
                            ksort($dataPAC); // sắp xếp theo key của array
                            if($dataPAC != NULL){
                                foreach($dataPAC as $index=>$item){
                                    if(strtoupper($item['Unit'])=='W'){
                                        $value = $item['Value']/1000;
                                    }else{
                                        $value = $item['Value'];
                                    }

                                    $data[] = array(
                                        'time'=> $index,
                                        'pac'=> $value,                                       
                                    );
                                }
                            }
                        }else{    
                            $this->chotSoLieuTheoKhuVuc($day,$customer_id,$area_id);                                              
                            $dataPAC = $this->M_dashboard->getPAC_khuVucDaChot($customer_id,$area_id,$day);
                            if($dataPAC != NULL){
                                $dataPAC = json_decode($dataPAC['DATA'],true);
                                ksort($dataPAC); // sắp xếp theo key của array
                                if($dataPAC != NULL){
                                    foreach($dataPAC as $index=>$item){
                                        if(strtoupper($item['Unit'])=='W'){
                                            $value = $item['Value']/1000;
                                        }else{
                                            $value = $item['Value'];
                                        }

                                        $data[] = array(
                                            'time'=> $index,
                                            'pac'=> $value
                                           
                                        );
                                    }
                                }
                            }
                        }
                    
                    
                }
                
                // xử lý đổi đơn vị
                // kiểm tra số lớn nhất > 1 hay không? nếu nhỏ hơn thì đổi lại thành kW
                $max = 0;
                $min = 0;
                if($data!=NULL){
                    foreach($data as $item){
                        if($max < $item['pac']){
                            $max=$item['pac'];
                        }
                        if($item['pac']!=0 && $min == 0){
                            $min=$item['pac'];
                        }

                        if($min > $item['pac'] && $item['pac']!=0 ){
                            $min=$item['pac'];
                        }
                    }
                }

                if($max < 1){ // nhỏ hơn 1 đổi lại thành kWh
                    $donViCuoiCung = "W";
                    foreach($data as $item){
                        $dataReturn[] = array(
                            'date'=>$day,
                            'time'=> $item['time'],
                            'area_id'=>$area_id,
                            'device_id'=>$device_id,
                            'pac'=> $item['pac'],
                            'unit'=> $donViCuoiCung,
                            'customer_id'=>$customer_id
                        );
                    }
                    $max= $max*1000;
                    $min= $min*1000;
                }else{
                    $donViCuoiCung = "kW";
                    foreach($data as $item){
                        $dataReturn[] = array(
                            'date'=>$day,
                            'time'=> $item['time'],
                            'area_id'=>$area_id,
                            'device_id'=>$device_id,
                            'pac'=> $item['pac'],
                            'unit'=> $donViCuoiCung,
                            'customer_id'=>$customer_id
                           
                            
                        );
                    }
                }
            break;
        }
    
        return  $dataReturn;

    }
    public function export(){
        $method = $this->input->method(TRUE); // Outputs: POST
        $query_select = "SELECT ngayNhanTinHieu, idThietBi, DAY_ENERGY_Unit, DAY_ENERGY_Value, PAC_Unit, PAC_Value, AC_A_UAC_Unit, AC_A_UAC_Value, AC_A_IAC_Unit, AC_A_IAC_Value, AC_B_UAC_Unit, AC_B_UAC_Value, AC_B_IAC_Unit, AC_B_IAC_Value, AC_C_UAC_Unit, AC_C_UAC_Value, AC_C_IAC_Unit, AC_C_IAC_Value FROM `tinhieu_tong` ";
        
        if($method =="GET"){
            $customer_id = $this->input->get("cusomter_id");
            $area_id = $this->input->get("area_id");
            $device_id = $this->input->get("device_id");
            $to_date = $this->input->get("to_date");
            $from_date = $this->input->get("from_date");
            $data_type = $this->input->get("data_type");

            $format = 'd/m/Y';
            $date = DateTime::createFromFormat($format,$from_date);          
            $date_from_convert = date_format($date, 'Y-m-d');       
            $date = DateTime::createFromFormat($format,$to_date);    
            $date_to_convert = date_format($date, 'Y-m-d');    
            $report_name = "export-".$customer_id."-".$area_id."-".strtotime("now").".csv";          
           
            if( $data_type == "total"){
                $result = $this->get_total_data_report( $customer_id,$area_id, $device_id,$date_from_convert,$date_to_convert);
             
                $header = array("ngayNhanTinHieu","idThietBi","DAY_ENERGY_Unit","DAY_ENERGY_Value","PAC_Unit","PAC_Value","AC_A_UAC_Unit","AC_A_UAC_Value","AC_A_IAC_Unit","AC_A_IAC_Value","AC_B_UAC_Unit","AC_B_UAC_Value","AC_B_IAC_Unit","AC_B_IAC_Value","AC_C_UAC_Unit","AC_C_UAC_Value","AC_C_IAC_Unit","AC_C_IAC_Value");
            }else {
                $result = [];
                $header = ["ngayNhanTinHieu","gioNhanTinHieu","idKhuVuc","idThietBi","PAC","Unit","idKhachHang"];

                while (strtotime($date_from_convert) <= strtotime($date_to_convert)) {
                                    
                    $newFormat = date("d/m/Y", strtotime($date_from_convert));  
                    $date_from_convert = date ("Y-m-d", strtotime("+1 day", strtotime($date_from_convert)));     
                    if($device_id != "all"){
                        $report_name = "export-".$customer_id."-".$area_id."-".$device_id."-".strtotime("now").".csv";     
                        $current_device_id = $device_id;
                        $result_date = $this->get_report_device_by_date( $customer_id,$area_id, $current_device_id, $newFormat);
                        if(count($result_date)>0){
                            $result = array_merge($result, $result_date);
                        }
                       
                    }else {
                        // get du lieu theo khu vuc
                        $sql_device = "select idThietBi from `thietbi` where taiKhoanKhachHang ='".$customer_id."'";                 
                        $query = $this->db->query( $sql_device);
                        $result_query = $query->result_array();                      
                        $result_date = $this->get_report_device_by_date( $customer_id,$area_id,"all", $newFormat);
                        if(count($result_date)>0){
                            $result = array_merge($result, $result_date);
                        }
                    }
                    
                   
                   
                 
                   // $date_by_date = DateTime::createFromFormat($format,$date_from_convert);     
                   // $result = $this->get_report_device_by_date( $customer_id,$area_id, $device_id,$from_date);
              }
              
            }
            
            header("Content-type: application/csv");           
            header("Pragma: no-cache");
            header("Expires: 0");
            header("Content-Disposition: attachment; filename=\"".$report_name."\"");
            $file = fopen('php://output', 'w');
            fputcsv($file, $header);
            foreach ($result as $key=>$line){ 
              fputcsv($file,$line); 
            }
            fclose($file); 
            exit; 
        }
    }
}
