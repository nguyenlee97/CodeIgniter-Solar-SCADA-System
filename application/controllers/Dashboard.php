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
 * @property M_dashboard $M_dashboard
 * @property Mglobal $Mglobal
 */
class Dashboard extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        ini_set('memory_limit', '2048M');
        $this->load->library('session');
        $this->load->helper('html');
        $this->load->helper('url');
        $this->load->Model("M_dashboard");
        $this->load->library("Global_function");
        if(!isset($_SESSION[LOGIN])){
            $_SESSION[LOGIN] = NULL;
        }

        if($_SESSION[LOGIN]==NULL){
            header('location:'.base_url('login')); exit();
        }
        // ini_set( 'serialize_precision', 3 );
        // exit();
    }

    public function index(){
		//echo "System investigate issue...Please wait.."; exit(0);
        $data_view['template'] = "dashboard/dashboard";
        $data_view['title'] = "Giám sát hệ thống";
        $data_view['iconTitle'] = "cil-tv";
        $data_view['breadcrumbLinkTitle'] = "";
        $data_view['data'] = NULL;

        $data_view['data']['thongTinCty'] = $_SESSION[LOGIN]['tenCongTy'];
        $data_view['data']['loaiTaiKhoan'] = $_SESSION[LOGIN]['loaiTaiKhoan'];

        $this->load->view('layout/layout',$data_view);
    }

    public function khuvuc(){
        $data_view['template'] = "dashboard/dashboardkhuvuc";
        $data_view['title'] = "Giám sát hệ thống";
        $data_view['breadcrumbLinkTitle'] = base_url('Dashboard');

        $data_view['iconTitle'] = "cil-tv";
        $data_view['subTitle'] = "Khu vực lắp đặt";
        $data_view['iconSubTitle'] = "cil-compass";

        $data_view['data'] = NULL;

        $data_view['data']['thongTinCty'] = $_SESSION[LOGIN]['tenCongTy'];
        $data_view['data']['loaiTaiKhoan'] = $_SESSION[LOGIN]['loaiTaiKhoan'];

        $data_view['data']['taiKhoanChinhSelected'] = $this->uri->segment(3);
        $data_view['data']['idKhuVucSelected'] = $this->uri->segment(4);


        $this->load->view('layout/layout',$data_view);
    }

    public function thietbi(){
        $data_view['template'] = "dashboard/dashboardthietbi";
        $data_view['title'] = "Khu vực giám sát";
        $data_view['breadcrumbLinkTitle'] = base_url('Dashboard/khuvuc/'.$this->uri->segment(3)."/".$this->uri->segment(4));

        $data_view['iconTitle'] = "cil-compass";
        $data_view['subTitle'] = "Thiết bị";
        $data_view['iconSubTitle'] = "cil-memory";

        $data_view['data'] = NULL;

        $data_view['data']['thongTinCty'] = $_SESSION[LOGIN]['tenCongTy'];
        $data_view['data']['loaiTaiKhoan'] = $_SESSION[LOGIN]['loaiTaiKhoan'];

        $data_view['data']['taiKhoanChinhSelected'] = $this->uri->segment(3);
        $data_view['data']['idKhuVucSelected'] = $this->uri->segment(4);
        $data_view['data']['idThietBiSelected'] = $this->uri->segment(5);

        $this->load->view('layout/layout',$data_view);
    }

    public function get_data_DashboardAll(){
        $user = $this->global_function->fixSql($this->input->post('user'));
        $idKhuVuc = $this->global_function->fixSql($this->input->post('idKhuVuc'));
        $idThietBi = $this->global_function->fixSql($this->input->post('idThietBi'));
        $option = $this->global_function->fixSql($this->input->post('option'));
        $year = $this->global_function->fixSql($this->input->post('year'));
        $monthYear = $this->global_function->fixSql($this->input->post('monthYear'));
        
        if($year==""){
            $year=gmdate('Y',time()+7*3600);
        }
        if($monthYear==""){
            $month=gmdate('m-Y',time()+7*3600);
        }

        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        switch($loaiTaiKhoan){
            case 'quantri':
                $taiKhoanChinh=$user;
            break;
            case 'nguoidung':
                $taiKhoanChinh=$_SESSION[LOGIN]['username'];
            break;
            case 'phu': default:
                $taiKhoanChinh = $_SESSION[LOGIN]['taiKhoanCapTren'];
                // kiểm tra id khu vực có được cấp quyền hay không
                if($idKhuVuc!=NULL){
                    if(!in_array($idKhuVuc,$_SESSION[LOGIN]['listQuyenIDKhuVuc'])){
                        echo json_encode($this->responseKhuVucFalse()); exit();
                    }
                }
            break;
        }

        $data=array();
        $dataReturn=array();
        switch($option){
            case 'day':
                $month = substr($monthYear,0,2);
                $year = substr($monthYear,3,4);
                $ghiChuThoiGian = "Tháng ".$month."-".$year;
                $soNgayTrongThang =$this->global_function->songay_trongthang($month,$year);
                
                for($t=1;$t<=$soNgayTrongThang;$t++){
                    $ngay = $t<10? '0'.$t : $t;
                    $strNgay = $ngay."/".$month."/".$year;
                    
                    // lấy sản lượng trong 1 ngày
                    $tongSanLuong = 0; // đơn vị MWh
                    $listSanLuongNgay = $this->M_dashboard->getSanLuongNgay($strNgay,$taiKhoanChinh,$idKhuVuc,$idThietBi);
                    if($listSanLuongNgay!=NULL){ // danh sách các thiết bị gửi dữ liệu về trong ngày, đã lọc theo max thời gian
                        $last_device = "";
                        foreach($listSanLuongNgay as $item){
                            if($loaiTaiKhoan=='phu' && !in_array($item['idKhuVucLapDat'],$_SESSION[LOGIN]['listQuyenIDKhuVuc'])){
                                continue;
                            }else{
                                if($last_device!=$item['idThietBi']){ // kiểm tra xem dữ liệu 1 thiết bị có trùng thời gian gửi cuối cùng không? Nếu trùng thì chỉ lấy 1 số
                                    $last_device = $item['idThietBi'];
                                    if(strtoupper ($item['DAY_ENERGY_Unit'])== strtoupper('kWh')){
                                        $value = $item['DAY_ENERGY_Value']/1000; // toàn bộ đổi ra MWh
                                    }else{
                                        $value = $item['DAY_ENERGY_Value'];
                                    }
                                    
                                    $tongSanLuong+=$value;
                                }
                            }
                        }
                        
                    }
                    
                    $data[] = array(
                        'label'=> $ngay,
                        'value'=> $tongSanLuong,
                    );


                }

                // kiểm tra số lớn nhất > 1 hay không? nếu nhỏ hơn thì đổi lại thành kWh
                $max = 0;
                if($data!=NULL){
                    foreach($data as $item){
                        if($max < $item['value']){
                            $max=$item['value'];
                        }
                    }
                }

                if($max < 1){ // nhỏ hơn 1 đổi lại thành kWh
                    $donViCuoiCung = "kWh";
                    foreach($data as $item){
                        $dataReturn[] = array(
                            'label'=> $item['label'],
                            'value'=> ($item['value']*1000),
                        );
                    }
                }else{
                    $donViCuoiCung = "MWh";
                    foreach($data as $index=>$item){
                        $dataReturn[] = array(
                            'label'=> $item['label'],
                            'value'=> $item['value'],
                        );
                    }
                }

            break;
            case 'month':
                $ghiChuThoiGian="Năm ".$year;
                for($t=1;$t<=12;$t++){
                    $thang = $t<10? '0'.$t : $t;
                    $tongSanLuong1Thang = 0; // đơn vị MWh
                    $listSanLuongThang = $this->M_dashboard->getSanLuongThang($thang,$year,$taiKhoanChinh,$idKhuVuc,$idThietBi);
                    if($listSanLuongThang!=NULL){ // danh sách các thiết bị gửi dữ liệu về trong tháng, đã lọc theo max thời gian
                        $last_device = "";
                        foreach($listSanLuongThang as $item){
                            if($loaiTaiKhoan=='phu' && !in_array($item['idKhuVucLapDat'],$_SESSION[LOGIN]['listQuyenIDKhuVuc'])){
                                continue;
                            }else{
                                if($last_device!=$item['idThietBi']){ // kiểm tra xem dữ liệu 1 thiết bị có trùng thời gian gửi cuối cùng không? Nếu trùng thì chỉ lấy 1 số
                                    $last_device = $item['idThietBi'];
                                    if(strtoupper ($item['MONTH_ENERGY_Unit']) == strtoupper('kWh')){
                                        $value = $item['MONTH_ENERGY_Value']/1000; // toàn bộ đổi ra MWh
                                    }else{
                                        $value = $item['MONTH_ENERGY_Value'];
                                    }
                                    $tongSanLuong1Thang+=$value;
                                }
                            }
                        }
                    }
                    $data[] = array(
                        'label'=> "T".$t,
                        'value'=> $tongSanLuong1Thang,
                    );
                }

                // kiểm tra số lớn nhất > 1 hay không? nếu nhỏ hơn thì đổi lại thành kWh
                $max = 0;
                if($data!=NULL){
                    foreach($data as $item){
                        if($max < $item['value']){
                            $max=$item['value'];
                        }
                    }
                }

                if($max < 1){ // nhỏ hơn 1 đổi lại thành kWh
                    $donViCuoiCung = "kWh";
                    foreach($data as $item){
                        $dataReturn[] = array(
                            'label'=> $item['label'],
                            'value'=> ($item['value']*1000),
                        );
                    }
                }else{
                    $donViCuoiCung = "MWh";
                    foreach($data as $item){
                        $dataReturn[] = array(
                            'label'=> $item['label'],
                            'value'=> $item['value'],
                        );
                    }
                }

            break;
            case 'year':
                $ghiChuThoiGian="Tất cả";
                // lấy danh sách các năm đang có số liệu
                $listNam = $this->M_dashboard->getListYearHaveValue($taiKhoanChinh,$idKhuVuc,$idThietBi);
                if($listNam!=NULL){
                    foreach($listNam as $nam){
                        $ghiChuThoiGian="Tất cả";
                        $tongSanLuong1Nam = 0; // đơn vị MWh
                        $listSanLuongNam = $this->M_dashboard->getSanLuongNam($nam['nam'],$taiKhoanChinh);
                        if($listSanLuongNam!=NULL){ // danh sách các thiết bị gửi dữ liệu về trong tháng, đã lọc theo max thời gian
                            $last_device = "";
                            foreach($listSanLuongNam as $item){
                                if($loaiTaiKhoan=='phu' && !in_array($item['idKhuVucLapDat'],$_SESSION[LOGIN]['listQuyenIDKhuVuc'])){
                                    continue;
                                }else{
                                    if($last_device!=$item['idThietBi']){ // kiểm tra xem dữ liệu 1 thiết bị có trùng thời gian gửi cuối cùng không? Nếu trùng thì chỉ lấy 1 số
                                        $last_device = $item['idThietBi'];
                                        if(strtoupper ($item['YEAR_ENERGY_Unit']) == strtoupper('kWh')){
                                            $value = $item['YEAR_ENERGY_Value']/1000; // toàn bộ đổi ra MWh
                                        }else{
                                            $value = $item['YEAR_ENERGY_Value'];
                                        }
                                        $tongSanLuong1Nam+=$value;
                                    }
                                }
                            }
                        }
                        $data[] = array(
                            'label'=> $nam['nam'],
                            'value'=> $tongSanLuong1Nam,
                        );
                    }
                }

                // kiểm tra số lớn nhất > 1 hay không? nếu nhỏ hơn thì đổi lại thành kWh
                $max = 0;
                if($data!=NULL){
                    foreach($data as $item){
                        if($max < $item['value']){
                            $max=$item['value'];
                        }
                    }
                }

                if($max < 1){ // nhỏ hơn 1 đổi lại thành kWh
                    $donViCuoiCung = "kWh";
                    foreach($data as $item){
                        $dataReturn[] = array(
                            'label'=> $item['label'],
                            'value'=> ($item['value']*1000),
                        );
                    }
                }else{
                    $donViCuoiCung = "MWh";
                    foreach($data as $item){
                        $dataReturn[] = array(
                            'label'=> $item['label'],
                            'value'=> $item['value'],
                        );
                    }
                }
            break;
        }
        // var_dump($dataReturn);
        $result = array(
            'state' => 'success',
            'alert' => 'Get data success !',
            'data' => $dataReturn,
            'donvi' =>$donViCuoiCung,
            'ghiChuThoiGian' => $ghiChuThoiGian,
        );
        echo json_encode($result);
    }

    public function get_data_DashboardPAC(){
        $user = $this->global_function->fixSql($this->input->post('user'));
        $idKhuVuc = $this->global_function->fixSql($this->input->post('idKhuVuc'));
        $idThietBi = $this->global_function->fixSql($this->input->post('idThietBi'));
        $option = $this->global_function->fixSql($this->input->post('option'));
        $year = $this->global_function->fixSql($this->input->post('year'));
        $monthYear = $this->global_function->fixSql($this->input->post('monthYear'));
        $day = $this->global_function->fixSql($this->input->post('day'));
        
        if($year==""){
            $year=gmdate('Y',time()+7*3600);
        }
        if($monthYear==""){
            $month=gmdate('m-Y',time()+7*3600);
        }

        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        switch($loaiTaiKhoan){
            case 'quantri':
                $taiKhoanChinh=$user;
            break;
            case 'nguoidung':
                $taiKhoanChinh=$_SESSION[LOGIN]['username'];

            break;
            case 'phu': default:
                $taiKhoanChinh = $_SESSION[LOGIN]['taiKhoanCapTren'];
                // kiểm tra id khu vực có được cấp quyền hay không
                if($idKhuVuc!=NULL){
                    if(!in_array($idKhuVuc,$_SESSION[LOGIN]['listQuyenIDKhuVuc'])){
                        echo json_encode($this->responseKhuVucFalse()); exit();
                    }
                }
            break;
        }

        $data=array();
        $dataReturn=array();
        switch($option){
            case 'hour': default:
                $ghiChuThoiGian = $day;
                if($idThietBi!=""){
                    // lấy PAC của 1 thiết bị
                    $dataPAC=$this->M_dashboard->getPAC_thietBiTheoNgay($taiKhoanChinh,$idKhuVuc,$idThietBi,$day);
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
                                'label'=> $label,
                                'value'=> $value,
                            );
                        }
                    }
                }else{
                    // lấy theo khu vực hoặc theo tài khoản
                    if($idKhuVuc==""){
                        // lấy PAC tất cả khu vực đang quản lý
                        $listDataPAC_cacKhuVuc = array();
                        $listIndex = array();
                        if($loaiTaiKhoan=='phu'){
                            $listIDKhuVuc = $_SESSION[LOGIN]['listQuyenIDKhuVuc'];
                            if($listIDKhuVuc!=NULL){
                                foreach($listIDKhuVuc as $item){
                                    $tmp = $this->M_dashboard->getPAC_khuVucDaChot($taiKhoanChinh,$item,$day);
                                    if(isset($tmp['DATA'])){
                                        $tmp=json_decode($tmp['DATA'],true);
                                        $listDataPAC_cacKhuVuc[] = $tmp;
                                        $listIndex += $tmp;
                                    }else{
                                        // chưa có dữ liệu chốt => chốt số liệu và lấy lại
                                        $this->chotSoLieuTheoKhuVuc($day,$taiKhoanChinh,$item);
                                        $tmp = $this->M_dashboard->getPAC_khuVucDaChot($taiKhoanChinh,$item,$day);
                                        if(isset($tmp['DATA'])){
                                            $tmp=json_decode($tmp['DATA'],true);
                                            $listDataPAC_cacKhuVuc[] = $tmp;
                                            $listIndex += $tmp;
                                        }
                                    }
                                }
                            }
                        }else{
                            // lấy danh sách khu vực đang quản lý từ Database
                            $listIDKhuVuc = $this->M_dashboard->getDS_idKhuVucQuanLy($taiKhoanChinh);
                            if($listIDKhuVuc !=NULL){
                                foreach($listIDKhuVuc as $item){
                                    $tmp = $this->M_dashboard->getPAC_khuVucDaChot($taiKhoanChinh,$item['id'],$day);
                                    if(isset($tmp['DATA'])){
                                        $tmp=json_decode($tmp['DATA'],true);
                                        $listDataPAC_cacKhuVuc[] = $tmp;
                                        $listIndex += $tmp;
                                    }else{
                                        // chưa có dữ liệu chốt => chốt số liệu và lấy lại
                                        $this->chotSoLieuTheoKhuVuc($day,$taiKhoanChinh,$item['id']);
                                        $tmp = $this->M_dashboard->getPAC_khuVucDaChot($taiKhoanChinh,$item['id'],$day);
                                        if(isset($tmp['DATA'])){
                                            $tmp=json_decode($tmp['DATA'],true);
                                            $listDataPAC_cacKhuVuc[] = $tmp;
                                            $listIndex += $tmp;
                                        }
                                    }
                                }
                            }
                        }
                        if($listIndex!=NULL){
                            ksort($listIndex);
                            foreach($listIndex as $index=>$item){
                                // value bằng tổng value
                                $value= 0;
                                foreach($listDataPAC_cacKhuVuc as $PACtungKhuVuc){
                                    if(isset($PACtungKhuVuc[$index])){
                                        if(strtoupper($PACtungKhuVuc[$index]['Unit'])=='W'){
                                            $value += (float)($PACtungKhuVuc[$index]['Value']/1000);
                                        }else{
                                            $value += (float)$PACtungKhuVuc[$index]['Value'];
                                        }
                                    }
                                    
                                }
                                $data[] = array(
                                    'label'=> $index,
                                    'value'=> $value,
                                );
                            }
                        }
                    }else{
                        // lấy PAC 1 khu vực
                        $dataPAC=$this->M_dashboard->getPAC_khuVucDaChot($taiKhoanChinh,$idKhuVuc,$day);
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
                                        'label'=> $index,
                                        'value'=> $value,
                                    );
                                }
                            }
                        }else{
                            // chưa có dữ liệu chốt => chốt số liệu và lấy lại
                            $this->chotSoLieuTheoKhuVuc($day,$taiKhoanChinh,$idKhuVuc);
                            $dataPAC=$this->M_dashboard->getPAC_khuVucDaChot($taiKhoanChinh,$idKhuVuc,$day);
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
                                            'label'=> $index,
                                            'value'=> $value,
                                        );
                                    }
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
                        if($max < $item['value']){
                            $max=$item['value'];
                        }
                        if($item['value']!=0 && $min == 0){
                            $min=$item['value'];
                        }

                        if($min > $item['value'] && $item['value']!=0 ){
                            $min=$item['value'];
                        }
                    }
                }

                if($max < 1){ // nhỏ hơn 1 đổi lại thành kWh
                    $donViCuoiCung = "W";
                    foreach($data as $item){
                        $dataReturn[] = array(
                            'label'=> $item['label'],
                            'value'=> ($item['value']*1000) ,
                        );
                    }
                    $max= $max*1000;
                    $min= $min*1000;
                }else{
                    $donViCuoiCung = "kW";
                    foreach($data as $item){
                        $dataReturn[] = array(
                            'label'=> $item['label'],
                            'value'=> $item['value'],
                        );
                    }
                }
            break;
        }
        
        $result = array(
            'state' => 'success',
            'alert' => 'Get data success !',
            'data' => $dataReturn,
            'donvi' =>$donViCuoiCung,
            'ghiChuThoiGian' => $ghiChuThoiGian,
            'PAC_max' => $max,
            'PAC_min' => $min,
        );
        echo json_encode($result);
    }

    public function get_data_DashboardPAC_old(){
        $user = $this->global_function->fixSql($this->input->post('user'));
        $idKhuVuc = $this->global_function->fixSql($this->input->post('idKhuVuc'));
        $idThietBi = $this->global_function->fixSql($this->input->post('idThietBi'));
        $option = $this->global_function->fixSql($this->input->post('option'));
        $year = $this->global_function->fixSql($this->input->post('year'));
        $monthYear = $this->global_function->fixSql($this->input->post('monthYear'));
        $day = $this->global_function->fixSql($this->input->post('day'));
        
        if($year==""){
            $year=gmdate('Y',time()+7*3600);
        }
        if($monthYear==""){
            $month=gmdate('m-Y',time()+7*3600);
        }

        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        switch($loaiTaiKhoan){
            case 'quantri':
                $taiKhoanChinh=$user;
            break;
            case 'nguoidung':
                $taiKhoanChinh=$_SESSION[LOGIN]['username'];

            break;
            case 'phu': default:
                $taiKhoanChinh = $_SESSION[LOGIN]['taiKhoanCapTren'];
                // kiểm tra id khu vực có được cấp quyền hay không
                if($idKhuVuc!=NULL){
                    if(!in_array($idKhuVuc,$_SESSION[LOGIN]['listQuyenIDKhuVuc'])){
                        echo json_encode($this->responseKhuVucFalse()); exit();
                    }
                }
            break;
        }

        $data=array();
        $dataReturn=array();
        switch($option){
            case 'hour': default:
                $ghiChuThoiGian = $day;
                // lấy danh sách các khoản thời gian trong ngày
                $first_timestamp=strtotime($this->global_function->convert_strDateTime_format_from_vi_to_en($day));
                $end_timestamp=$first_timestamp+24*3600;
                if($idThietBi == ""){
                    $khoanCachThoiGian=30*60; // 30 phút
                }else{
                    $khoanCachThoiGian=5*60; // 5 phút
                }
                
                $last_timestamp=$first_timestamp;
                $arr_time=array();
                while($last_timestamp<$end_timestamp){
                    // lấy danh sách các khoản thời gian 
                    $from=$last_timestamp;
                    $to=$from+$khoanCachThoiGian;
                    $arr_time[] = array(
                        'from' => $from,
                        'to' => $to,
                        'str_from' => gmdate('d/m/Y H:i:s',$from),
                        'str_to' => gmdate('d/m/Y H:i:s',$to),
                        'label' => gmdate('H:i',$from),
                    );

                    $last_timestamp=$to;
                }

                // lấy số liệu theo giờ của thiết bị
                $listTinHieuThietBiTrongNgay = $this->M_dashboard->getdataTrongNgay($day,$taiKhoanChinh,$idKhuVuc,$idThietBi);
                $listTinHieuThietBiTrongNgay =$this->xoaTrungDataThietBi($listTinHieuThietBiTrongNgay);
                
                if($arr_time!=NULL){
                    foreach($arr_time as $item){
                        $tongPAC=0; // 1 khoản thời gian
                        if($listTinHieuThietBiTrongNgay!=NULL){ // danh sách các thiết bị gửi dữ liệu về trong ngày, đã lọc theo max thời gian
                            foreach($listTinHieuThietBiTrongNgay as $thietbi){
                                // duyệt từng thiết bị
                                if($loaiTaiKhoan=='phu' && !in_array($thietbi['idKhuVucLapDat'],$_SESSION[LOGIN]['listQuyenIDKhuVuc'])){
                                    continue;
                                }else{
                                    $listData=json_decode($thietbi['duLieuTheoGioTomTat'],true);
                                    $dataHour = $this->tinHieuDauTienTrongKhoanThoiGian($listData,$item['from'],$item['to']);
                                    if($dataHour!=NULL){
                                        $DATA=$dataHour['DATA'];
                                        if(strtoupper($DATA['PAC']['Unit'])=='W'){
                                            $tongPAC+=$DATA['PAC']['Value']/1000;
                                        }else{
                                            $tongPAC+=$DATA['PAC']['Value'];
                                        }
                                    }
                                }
                            }
                        }

                        // tổng PAC tất cả thiết bị trong khoản thời gian này , đơn vị đều là kW
                        $data[] = array(
                            'label'=> $item['label'],
                            'value'=> $tongPAC,
                        );
                    }
                }

                // xử lý đổi đơn vị
                // kiểm tra số lớn nhất > 1 hay không? nếu nhỏ hơn thì đổi lại thành kWh
                $max = 0;
                $min = 0;
                if($data!=NULL){
                    foreach($data as $item){
                        if($max < $item['value']){
                            $max=$item['value'];
                        }
                        if($item['value']!=0 && $min == 0){
                            $min=$item['value'];
                        }

                        if($min > $item['value'] && $item['value']!=0 ){
                            $min=$item['value'];
                        }
                    }
                }

                if($max < 1){ // nhỏ hơn 1 đổi lại thành kWh
                    $donViCuoiCung = "W";
                    foreach($data as $item){
                        $dataReturn[] = array(
                            'label'=> $item['label'],
                            'value'=> ($item['value']*1000) ,
                        );
                    }
                    $max= $max*1000;
                    $min= $min*1000;
                }else{
                    $donViCuoiCung = "kW";
                    foreach($data as $item){
                        $dataReturn[] = array(
                            'label'=> $item['label'],
                            'value'=> $item['value'],
                        );
                    }
                }
            break;
        }
        
        $result = array(
            'state' => 'success',
            'alert' => 'Get data success !',
            'data' => $dataReturn,
            'donvi' =>$donViCuoiCung,
            'ghiChuThoiGian' => $ghiChuThoiGian,
            'PAC_max' => $max,
            'PAC_min' => $min,
        );
        echo json_encode($result);
    }

    public function get_EnergyCurrentOfKhuVuc(){
        $user = $this->global_function->fixSql($this->input->post('user'));
        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        switch($loaiTaiKhoan){
            case 'quantri':
                $taiKhoanChinh=$user;
            break;
            case 'nguoidung':
                $taiKhoanChinh=$_SESSION[LOGIN]['username'];
            break;
            case 'phu': default:
                $taiKhoanChinh = $_SESSION[LOGIN]['taiKhoanCapTren'];
            break;
        }

        // lấy danh sách các khu vực của taikhoanchinh
        $listKhuVuc = $this->M_dashboard->getDanhSachKhuVuc($taiKhoanChinh);
     
        $resultData=array();

        if($listKhuVuc!=NULL){
            foreach($listKhuVuc as $khuvuc){
                if($loaiTaiKhoan=='phu' && !in_array($khuvuc['id'],$_SESSION[LOGIN]['listQuyenIDKhuVuc'])){
                    continue;
                }else{
                    $tongSanLuongNgay = 0;
                    $tongSanLuongThang= 0;
                    $donviNgay="";
                    $donviThang="";
    
                    // lấy danh sách sản lượng ngày theo khu vực
                    $strNgay=gmdate('d/m/Y H:i:s',time()+7*3600);
                    $listSanLuongKhuVuc_Ngay = $this->M_dashboard->getSanLuongNgay($strNgay,$taiKhoanChinh,$khuvuc['id']);
                    $listSanLuongKhuVuc_Ngay = $this->locTrungVaTinhTongGiaTri($listSanLuongKhuVuc_Ngay,'DAY');
                    $tongSanLuongNgay = $listSanLuongKhuVuc_Ngay['tongSanLuong'];
                    if($tongSanLuongNgay<1){
                        $tongSanLuongNgay=$tongSanLuongNgay*1000;
                        $donviNgay="kWh";
                    }else{
                        $donviNgay="MWh";
                    }
                    
    
                    // lấy danh sách sản lượng tháng theo từng khu vực
                    $thang=gmdate('m',time()+7*3600);
                    $nam=gmdate('Y',time()+7*3600);
                    $listSanLuongKhuVuc_Thang = $this->M_dashboard->getSanLuongThang($thang,$nam,$taiKhoanChinh,$khuvuc['id']);
                 
                    $listSanLuongKhuVuc_thang = $this->locTrungVaTinhTongGiaTri($listSanLuongKhuVuc_Thang,'MONTH');
                    $tongSanLuongThang = $listSanLuongKhuVuc_thang['tongSanLuong'];
                    if($tongSanLuongThang<1){
                        $tongSanLuongThang=$tongSanLuongThang*1000;
                        $donviThang="kWh";
                    }else{
                        $donviThang="MWh";
                    }

                    // lấy danh sách sản lượng tổng theo từng khu vực
                    $thang=gmdate('m',time()+7*3600);
                    $nam=gmdate('Y',time()+7*3600);
                    $listSanLuongKhuVuc_Total = $this->M_dashboard->getSanLuongTong($taiKhoanChinh,$khuvuc['id']);
                 
                    $listSanLuongKhuVuc_Total = $this->locTrungVaTinhTongGiaTri($listSanLuongKhuVuc_Total,'TOTAL');
                    $tongSanLuongTotal = $listSanLuongKhuVuc_Total['tongSanLuong'];
                    if($tongSanLuongTotal<1){
                        $tongSanLuongTotal=$tongSanLuongTotal*1000;
                        $donviTotal="kWh";
                    }else{
                        $donviTotal="MWh";
                    }
    
    
                    // lấy số lượng thiết bị & online
                    $soLuongThietBi= $this->M_dashboard->getSoLuongThietBi($taiKhoanChinh,$khuvuc['id']);
    
                    // lấy PAC theo khu vuc
                    $listThietBi_PAC = $this->M_dashboard->getPACCurrent($taiKhoanChinh,$khuvuc['id']);
                    // var_dump($listThietBi_PAC);
                    $listThietBi_PAC = $this->xoaTrungDataThietBi($listThietBi_PAC);
                    $tongPAC = 0;
                    if($listThietBi_PAC!=NULL){
                        foreach($listThietBi_PAC as $PAC){
                            if(strtoupper ($PAC['PAC_Unit']) == 'W'){
                                $tongPAC += $PAC['PAC_Value']/1000; // toàn bộ đổi ra kW
                            }else{
                                $tongPAC +=$PAC['PAC_Value'];
                            }
                        }
                    }
                    if($tongPAC < 1){
                        $tongPAC = $tongPAC*1000;
                        $donViPAC="W";
                    }else{
                        $donViPAC="kW";
                        $tongPAC = $tongPAC;
                    }
                    //-- // lấy PAC theo khu vuc
    
                    // tính tiền
                    if($donviNgay=='kWh'){
                        $money_day=$khuvuc['donGia']*$tongSanLuongNgay;
                    }else{
                        $money_day=$khuvuc['donGia']*$tongSanLuongNgay*1000;
                    }
                    $money_day=$this->global_function->formatSoTien($money_day);

                    if($donviThang=='kWh'){
                        $money_month=$khuvuc['donGia']*$tongSanLuongThang;
                    }else{
                        $money_month=$khuvuc['donGia']*$tongSanLuongThang*1000;
                    }
                    $money_month=$this->global_function->formatSoTien($money_month);
    
                    if($donviTotal=='kWh'){
                        $money_total=$khuvuc['donGia']*$tongSanLuongTotal;
                    }else{
                        $money_total=$khuvuc['donGia']*$tongSanLuongTotal*1000;
                    }
                    $money_total=$this->global_function->formatSoTien($money_total);

                    $resultData[] = array(
                        'idKhuVucLapDat' => $khuvuc['id'],
                        'day' => array(
                            'Unit' => $donviNgay,
                            'Value' => $tongSanLuongNgay,
                            'tien' => $money_day
                        ),
                        'month' => array(
                            'Unit' => $donviThang,
                            'Value' => $tongSanLuongThang,
                            'tien' => $money_month
                        ),
                        'total' => array(
                            'Unit' => $donviTotal,
                            'Value' => $tongSanLuongTotal,
                            'tien' => $money_total,
                        ),
                        'tenKhuVuc' => $khuvuc['tenKhuVuc'],
                        'soluongInverter' => $soLuongThietBi['tong'],
                        'soluongInverterOnline' => $soLuongThietBi['Online'],
                        'PAC' => array(
                            'Unit' => $donViPAC,
                            'Value' => (float)$tongPAC,
                            'chiTieuMax' => 0,
                            'chiTieuMin' => 0,
                        ),
                    );
                }
                
            }

        }

        $result = array(
            'state' => 'success',
            'alert' => 'Get data success !',
            'data' => $resultData,
        );
        echo json_encode($result); exit();
    }

    private function xoaTrungDataThietBi($data){
        $last_device = "";
        $dataFixed=NULL;
        if($data!=NULL){
            foreach($data as $item){
                if($last_device!=$item['idThietBi']){ 
                    $last_device = $item['idThietBi'];
                    $dataFixed[] = $item;
                }
            }
        }
        return $dataFixed;
    }

    // lọc dữ liệu trùng dòng và tính tổng sản lượng, tổng sản lượng đổi đơn vị thành MWh
    private function locTrungVaTinhTongGiaTri($data,$loaiSoLieu){// loại số liệu là lấy theo ngày, tháng hay năm
        $last_device = "";
        $dataFixed=NULL;
        $tongSanLuong = 0;
        if($data!=NULL){
            foreach($data as $item){
                if($last_device!=$item['idThietBi']){ 
                    $last_device = $item['idThietBi'];
                    $dataFixed[] = $item;
                    $value=0;
                    switch(strtoupper($loaiSoLieu)){
                        case 'DAY':
                            if(strtoupper ($item['DAY_ENERGY_Unit']) == 'KWH'){
                                $value = $item['DAY_ENERGY_Value']/1000; // toàn bộ đổi ra MWh
                            }else{
                                $value=$item['DAY_ENERGY_Value'];
                            }
                            $tongSanLuong+=$value;
                        break;
                        case 'MONTH':
                            if(strtoupper ($item['MONTH_ENERGY_Unit']) == 'KWH'){
                                $value = $item['MONTH_ENERGY_Value']/1000; // toàn bộ đổi ra MWh
                            }else{
                                $value=$item['MONTH_ENERGY_Value'];
                            }
                            $tongSanLuong+=$value;
                        break;
                        case 'YEAR':
                            if(strtoupper ($item['YEAR_ENERGY_Unit']) == 'KWH'){
                                $value = $item['YEAR_ENERGY_Value']/1000; // toàn bộ đổi ra MWh
                            }else{
                                $value=$item['YEAR_ENERGY_Value'];
                            }
                            $tongSanLuong+=$value;
                        break;
                        case 'TOTAL':
                            if(strtoupper ($item['TOTAL_ENERGY_Unit']) == 'KWH'){
                                $value = $item['TOTAL_ENERGY_Value']/1000; // toàn bộ đổi ra MWh
                            }else{
                                $value=$item['TOTAL_ENERGY_Value'];
                            }
                            $tongSanLuong+=$value;
                        break;
                   }
                }
            }
        }
        return array(
            'dataFixed' => $dataFixed,
            'tongSanLuong' => $tongSanLuong,
        );
    } 

    private function responseKhuVucFalse(){
        $result = array(
            'state' => 'error',
            'alert' => "Bạn không có quyển truy câp khu lắp đặt vực này"
        );
        return $result;
    }

    public function get_EnergyCurrentOfDevice(){
        $user = $this->global_function->fixSql($this->input->post('user'));
        $idKhuVuc = $this->global_function->fixSql($this->input->post('idKhuVuc'));
        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        switch($loaiTaiKhoan){
            case 'quantri':
                $taiKhoanChinh=$user;
            break;
            case 'nguoidung':
                $taiKhoanChinh=$_SESSION[LOGIN]['username'];
            break;
            case 'phu': default:
                $taiKhoanChinh = $_SESSION[LOGIN]['taiKhoanCapTren'];
                // kiểm tra id khu vực có được cấp quyền hay không
                if($idKhuVuc!=NULL){
                    if(!in_array($idKhuVuc,$_SESSION[LOGIN]['listQuyenIDKhuVuc'])){
                        echo json_encode($this->responseKhuVucFalse()); exit();
                    }
                }
            break;
        }

        // lấy thông tin khu vực
        $khuvuc = $this->M_dashboard->getThongTinKhuVuc($taiKhoanChinh,$idKhuVuc);
        // lấy danh sách các thiết bị trong khu vực có idKhuVuc của taikhoanchinh
        $listThietBi = $this->M_dashboard->getDanhSachThietBiTheoKhuVuc($taiKhoanChinh,$idKhuVuc);
        $resultData=array();

        if($listThietBi!=NULL){
            foreach($listThietBi as $thietbi){
                $tongSanLuongNgay = 0;
                $tongSanLuongThang= 0;
                $donviNgay="";
                $donviThang="";

                // lấy danh sách sản lượng ngày theo thiet bi
                $strNgay=gmdate('d/m/Y H:i:s',time()+7*3600);
                $listSanLuongKhuVuc_Ngay = $this->M_dashboard->getSanLuongNgay($strNgay,$taiKhoanChinh,$idKhuVuc,$thietbi['idThietBi']);
                $listSanLuongKhuVuc_Ngay = $this->locTrungVaTinhTongGiaTri($listSanLuongKhuVuc_Ngay,'DAY');
                $tongSanLuongNgay = $listSanLuongKhuVuc_Ngay['tongSanLuong'];
                if($tongSanLuongNgay<1){
                    $tongSanLuongNgay=$tongSanLuongNgay*1000;
                    $donviNgay="kWh";
                }else{
                    $donviNgay="MWh";
                }
                

                // lấy danh sách sản lượng tháng theo thiet bi
                $thang=gmdate('m',time()+7*3600);
                $nam=gmdate('Y',time()+7*3600);
                $listSanLuongKhuVuc_Thang = $this->M_dashboard->getSanLuongThang($thang,$nam,$taiKhoanChinh,$idKhuVuc,$thietbi['idThietBi']);
             
                $listSanLuongKhuVuc_thang = $this->locTrungVaTinhTongGiaTri($listSanLuongKhuVuc_Thang,'MONTH');
                $tongSanLuongThang = $listSanLuongKhuVuc_thang['tongSanLuong'];
                if($tongSanLuongThang<1){
                    $tongSanLuongThang=$tongSanLuongThang*1000;
                    $donviThang="kWh";
                }else{
                    $donviThang="MWh";
                }

                // Kiểm tra hiện trạng online
                $infoDevice= $this->M_dashboard->getInfoThietBiIfOnline($taiKhoanChinh,$idKhuVuc,$thietbi['idThietBi']);

                if($infoDevice!=NULL){
                    $Online=1;
                    $tongPAC=$infoDevice['PAC_Value'];
                    $donViPAC=$infoDevice['PAC_Unit'];
                }else{
                    $Online=0;
                    // lấy thông tin nhận sau cùng
                    $infoDevice = $this->M_dashboard->getInfoThietBiLast($taiKhoanChinh,$idKhuVuc,$thietbi['idThietBi']);        
                    $tongPAC="";
                    $donViPAC="";   
                }

                if($infoDevice!=NULL){
                    $ngayNhanTinHieuSauCung=$this->global_function->format_thoigian($infoDevice['ngayNhanTinHieu'],'d/m/Y H:i:s');
                    $donviToTal=$infoDevice['TOTAL_ENERGY_Unit'];
                    $TongToTal=$infoDevice['TOTAL_ENERGY_Value'];
                }else{
                    $ngayNhanTinHieuSauCung="Không";
                    $donviToTal="";
                    $TongToTal="";
                }

                // tính tiền
                if($donviNgay=='kWh'){
                    $money_day=$khuvuc['donGia']*$tongSanLuongNgay;
                }else{
                    $money_day=$khuvuc['donGia']*$tongSanLuongNgay*1000;
                }
                $money_day=$this->global_function->formatSoTien($money_day);

                if($donviThang=='kWh'){
                    $money_month=$khuvuc['donGia']*$tongSanLuongThang;
                }else{
                    $money_month=$khuvuc['donGia']*$tongSanLuongThang*1000;
                }
                $money_month=$this->global_function->formatSoTien($money_month);

                if($donviToTal=='kWh'){
                    $money_total=$khuvuc['donGia']*$TongToTal;
                    if($TongToTal>1000){
                        $TongToTal=$TongToTal/1000;
                        $donviToTal="MWh";
                    }
                }else{
                    $money_total=$khuvuc['donGia']*$TongToTal*1000;
                }
                $money_total=$this->global_function->formatSoTien($money_total);

                $resultData[] = array(
                    'idThietBi' =>$thietbi['idThietBi'],
                    'idKhuVuc' =>$idKhuVuc,
                    'day' => array(
                        'Unit' => $donviNgay,
                        'Value' => $tongSanLuongNgay,
                        'tien' => $money_day
                    ),
                    'month' => array(
                        'Unit' => $donviThang,
                        'Value' => $tongSanLuongThang,
                        'tien' => $money_month
                    ),
                    'total' => array(
                        'Unit' => $donviToTal,
                        'Value' => $TongToTal,
                        'tien' => $money_total,
                    ),
                    'tenThietBi' => $thietbi['tenThietBi'],
                    'Online' => $Online,
                    'ngayNhanTinHieuSauCung' => $ngayNhanTinHieuSauCung,
                    'PAC' => array(
                        'Unit' => $donViPAC,
                        'Value' => $tongPAC,
                    ),

                );
            }

        }

        $result = array(
            'state' => 'success',
            'alert' => 'Get data success !',
            'data' => $resultData,
        );
        echo json_encode($result); exit();
    }

    public function get_InfoDevice(){
        $user = $this->global_function->fixSql($this->input->post('user'));
        $idKhuVuc = $this->global_function->fixSql($this->input->post('idKhuVuc'));
        $idThietBi = $this->global_function->fixSql($this->input->post('idThietBi'));
        $loaiTaiKhoan = $_SESSION[LOGIN]['loaiTaiKhoan'];
        switch($loaiTaiKhoan){
            case 'quantri':
                $taiKhoanChinh=$user;
            break;
            case 'nguoidung':
                $taiKhoanChinh=$_SESSION[LOGIN]['username'];
            break;
            case 'phu': default:
                $taiKhoanChinh = $_SESSION[LOGIN]['taiKhoanCapTren'];
                // kiểm tra id khu vực có được cấp quyền hay không
                if($idKhuVuc!=NULL){
                    if(!in_array($idKhuVuc,$_SESSION[LOGIN]['listQuyenIDKhuVuc'])){
                        echo json_encode($this->responseKhuVucFalse()); exit();
                    }
                }
                
            break;
        }

        // lấy thông tin khu vực
        $khuvuc = $this->M_dashboard->getThongTinKhuVuc($taiKhoanChinh,$idKhuVuc);

        // lấy thông tin của thiết bị
        $thietbi = $this->M_dashboard->getThongTinThietBi($taiKhoanChinh,$idKhuVuc,$idThietBi);

        $tongSanLuongNgay = 0;
        $tongSanLuongThang= 0;
        $donviNgay="";
        $donviThang="";

        // lấy danh sách sản lượng ngày theo thiet bi
        $strNgay=gmdate('d/m/Y H:i:s',time()+7*3600);
        $listSanLuongKhuVuc_Ngay = $this->M_dashboard->getSanLuongNgay($strNgay,$taiKhoanChinh,$idKhuVuc,$idThietBi);
        $listSanLuongKhuVuc_Ngay = $this->locTrungVaTinhTongGiaTri($listSanLuongKhuVuc_Ngay,'DAY');
        $tongSanLuongNgay = $listSanLuongKhuVuc_Ngay['tongSanLuong'];
        if($tongSanLuongNgay<1){
            $tongSanLuongNgay=$tongSanLuongNgay*1000;
            $donviNgay="kWh";
        }else{
            $donviNgay="MWh";
        }
        

        // lấy danh sách sản lượng tháng theo thiet bi
        $thang=gmdate('m',time()+7*3600);
        $nam=gmdate('Y',time()+7*3600);
        $listSanLuongKhuVuc_Thang = $this->M_dashboard->getSanLuongThang($thang,$nam,$taiKhoanChinh,$idKhuVuc,$idThietBi);
        
        $listSanLuongKhuVuc_thang = $this->locTrungVaTinhTongGiaTri($listSanLuongKhuVuc_Thang,'MONTH');
        $tongSanLuongThang = $listSanLuongKhuVuc_thang['tongSanLuong'];
        if($tongSanLuongThang<1){
            $tongSanLuongThang=$tongSanLuongThang*1000;
            $donviThang="kWh";
        }else{
            $donviThang="MWh";
        }

        // Kiểm tra hiện trạng online
        $infoDevice= $this->M_dashboard->getInfoThietBiIfOnline($taiKhoanChinh,$idKhuVuc,$idThietBi);

        if($infoDevice!=NULL){
            $Online=1;
            $tongPAC=$infoDevice['PAC_Value'];
            $donViPAC=$infoDevice['PAC_Unit'];
            $thongTinPhu = array(
                'FREQUENCY_AC_Unit' => $infoDevice['FREQUENCY_AC_Unit'],
                'FREQUENCY_AC_Value' => $infoDevice['FREQUENCY_AC'],
                'INTERNAL_TEMP_Unit' => $infoDevice['INTERNAL_TEMP_Unit'],
                'INTERNAL_TEMP_Value' => $infoDevice['INTERNAL_TEMP_Value'],
                'NUM_AC' => $infoDevice['NUM_AC'],
                'AC_A_UAC_Unit' => $infoDevice['AC_A_UAC_Unit'],
                'AC_A_UAC_Value' => $infoDevice['AC_A_UAC_Value'],
                'AC_A_IAC_Unit' => $infoDevice['AC_A_IAC_Unit'],
                'AC_A_IAC_Value' => $infoDevice['AC_A_IAC_Value'],
                'AC_B_UAC_Unit' => $infoDevice['AC_B_UAC_Unit'],
                'AC_B_UAC_Value' => $infoDevice['AC_B_UAC_Value'],
                'AC_B_IAC_Unit' => $infoDevice['AC_B_IAC_Unit'],
                'AC_B_IAC_Value' => $infoDevice['AC_B_IAC_Value'],
                'AC_C_UAC_Unit' => $infoDevice['AC_C_UAC_Unit'],
                'AC_C_UAC_Value' => $infoDevice['AC_C_UAC_Value'],
                'AC_C_IAC_Unit' => $infoDevice['AC_C_IAC_Unit'],
                'AC_C_IAC_Value' => $infoDevice['AC_C_IAC_Value']
            );
        }else{
            $Online=0;
            // lấy thông tin nhận sau cùng
            $infoDevice = $this->M_dashboard->getInfoThietBiLast($taiKhoanChinh,$idKhuVuc,$idThietBi);        
            $tongPAC="";
            $donViPAC="";   
            $thongTinPhu = array(
                'FREQUENCY_AC_Unit' => "",
                'FREQUENCY_AC_Value' => "",
                'INTERNAL_TEMP_Unit' => "",
                'INTERNAL_TEMP_Value' => "",
                'NUM_AC' => "",
                'AC_A_UAC_Unit' => "",
                'AC_A_UAC_Value' => "",
                'AC_A_IAC_Unit' => "",
                'AC_A_IAC_Value' => "",
                'AC_B_UAC_Unit' => "",
                'AC_B_UAC_Value' => "",
                'AC_B_IAC_Unit' => "",
                'AC_B_IAC_Value' => "",
                'AC_C_UAC_Unit' => "",
                'AC_C_UAC_Value' => "",
                'AC_C_IAC_Unit' => "",
                'AC_C_IAC_Value' => ""
            );
        }

        if($infoDevice!=NULL){
            $ngayNhanTinHieuSauCung=$this->global_function->format_thoigian($infoDevice['ngayNhanTinHieu'],'d/m/Y H:i:s');
            $String = $this->M_dashboard->getInfoString($idThietBi,$infoDevice['id']);
            $headData = $this->M_dashboard->getInfoHead($idThietBi);
            if(isset($headData['HEAD'])){
                $head = json_decode($headData['HEAD'],true);
            }else{
                $head=array();
            }
            
       
            $donviToTal=$infoDevice['TOTAL_ENERGY_Unit'];
            $TongToTal=$infoDevice['TOTAL_ENERGY_Value'];
        }else{
            $head=array();
            $ngayNhanTinHieuSauCung="Không";
            $String=NULL;
            $donviToTal="";
            $TongToTal="";
        }
        
        // lấy thông tin STRING
        $dataString = array();
        if($String!=NULL){
            foreach($String as $item){
                $dataString[] = array(
                    'idString' => $item['idString'],
                    'PV_UDC_Unit' => $item['PV_UDC_Unit'],
                    'PV_UDC_Value' => $item['PV_UDC_Value'],
                    'PV_IDC_Unit' => $item['PV_IDC_Unit'],
                    'PV_IDC_Value' => $item['PV_IDC_Value'],
                );
            }
        }
        // -- // lấy thông tin STRING

        // lấy các thông tin phụ
        

        // tính tiền
        if($donviNgay=='kWh'){
            $money_day=$khuvuc['donGia']*$tongSanLuongNgay;
        }else{
            $money_day=$khuvuc['donGia']*$tongSanLuongNgay*1000;
        }
        $money_day=$this->global_function->formatSoTien($money_day);
        if($donviThang=='kWh'){
            $money_month=$khuvuc['donGia']*$tongSanLuongThang;
        }else{
            $money_month=$khuvuc['donGia']*$tongSanLuongThang*1000;
        }
        $money_month=$this->global_function->formatSoTien($money_month);

        if($donviToTal=='kWh'){
            $money_total=$khuvuc['donGia']*$TongToTal;
            if($TongToTal>1000){
                $TongToTal=$TongToTal/1000;
                $donviToTal="MWh";
            }
        }else{
            $money_total=$khuvuc['donGia']*$TongToTal*1000;
        }
        $money_total=$this->global_function->formatSoTien($money_total);
        // // tính tiền

        $resultData = array(
            'idThietBi' =>$idThietBi,
            'idKhuVuc' =>$idKhuVuc,
            'day' => array(
                'Unit' => $donviNgay,
                'Value' => $tongSanLuongNgay,
                'tien' => $money_day
            ),
            'month' => array(
                'Unit' => $donviThang,
                'Value' => $tongSanLuongThang,
                'tien' => $money_month
            ),
            'total' => array(
                'Unit' => $donviToTal,
                'Value' => $TongToTal,
                'tien' => $money_total,
            ),
            'tenThietBi' => $thietbi['tenThietBi'],
            'Online' => $Online,
            'ngayNhanTinHieuSauCung' => $ngayNhanTinHieuSauCung,
            'PAC' => array(
                'Unit' => $donViPAC,
                'Value' => $tongPAC,
            ),
            'subData' => $thongTinPhu

        );

        $result = array(
            'state' => 'success',
            'alert' => 'Get data success !',
            'data' => $resultData,
            'dataString' => $dataString,
            'head' => $head,
        );
        echo json_encode($result); exit();
    }

    public function tinHieuDauTienTrongKhoanThoiGian($listData,$from,$to){ 
        $listData=$this->global_function->array_sort($listData,'TimestampInt',SORT_DESC);
        if($listData!=NULL){
            foreach($listData as $item){
                // $item=(array)$item;
                if($item['TimestampInt']>$from && $item['TimestampInt']<$to ){
                    return $item;
                }
            }
        }

        return NULL;
    }

    // -------------------------- -------------------------------------------------------------
    private function chotSoLieuTheoKhuVuc($day,$taiKhoanChinh,$idKhuVuc){
        // // lấy danh sách các khoản thời gian trong ngày
        $first_timestamp=strtotime($this->global_function->convert_strDateTime_format_from_vi_to_en($day));
        $end_timestamp=$first_timestamp+24*3600;
        $khoanCachThoiGian=5*60; // 5 phút
        
        $last_timestamp=$first_timestamp;
        $arr_time=array();
        while($last_timestamp<$end_timestamp){
            // lấy danh sách các khoản thời gian 
            $from=$last_timestamp;
            $to=$from+$khoanCachThoiGian;
            $arr_time[] = array(
                'from' => $from,
                'to' => $to,
                'str_from' => gmdate('d/m/Y H:i:s',$from),
                'str_to' => gmdate('d/m/Y H:i:s',$to),
                'label' => gmdate('H:i',$from),
            );

            $last_timestamp=$to;
        }

        // lấy số liệu theo giờ của thiết bị
        $listTinHieuThietBiTrongNgay = $this->M_dashboard->getdataTrongNgay($day,$taiKhoanChinh,$idKhuVuc,'');
        $listTinHieuThietBiTrongNgay =$this->xoaTrungDataThietBi($listTinHieuThietBiTrongNgay);
        $data=array();
        if($arr_time!=NULL){
            foreach($arr_time as $item){
                $tongPAC=0; // 1 khoản thời gian
                if($listTinHieuThietBiTrongNgay!=NULL){ // danh sách các thiết bị gửi dữ liệu về trong ngày, đã lọc theo max thời gian
                    foreach($listTinHieuThietBiTrongNgay as $thietbi){
                        // duyệt từng thiết bị
                        $listData=json_decode($thietbi['duLieuTheoGioTomTat'],true);
                        $dataHour = $this->tinHieuDauTienTrongKhoanThoiGian($listData,$item['from'],$item['to']);
                        if($dataHour!=NULL){
                            $DATA=$dataHour['DATA'];
                            if(strtoupper($DATA['PAC']['Unit'])=='W'){
                                $tongPAC+=$DATA['PAC']['Value']/1000;
                            }else{
                                $tongPAC+=$DATA['PAC']['Value'];
                            }
                        }
                    }
                }

                // tổng PAC tất cả thiết bị trong khoản thời gian này , đơn vị đều là kW
                $data[$item['label']] = array(
                    'Value'=> $tongPAC,
                    'Unit'=> 'KW',
                );
            }
        }

        $dataDaChot_json = json_encode($data);
        $this->M_dashboard->insertChoSoLieuPAC($taiKhoanChinh,$idKhuVuc,$day,$dataDaChot_json);
    }
    // ----------------------------------------------------------------------------------------

    public function test(){
        $idKhuVuc = $this->global_function->fixSql($this->input->get('idThietBi'));

        $getData = $this->M_dashboard->test($idKhuVuc);
        $getData = json_decode($getData,true);

        if($getData!=NULL){
            $last="";
            foreach($getData as  $item){
                if($last==""){
                    $last=$item['DATA']['TOTAL_ENERGY']['Value'];
                }
                echo "<div>";
                echo "Timestamp: ".$item['Timestamp']." ; DAY_VALUE: ".$item['DATA']['DAY_ENERGY']['Value']."; TOTAL_VALUE: ".$item['DATA']['TOTAL_ENERGY']['Value'];
                if($last > $item['DATA']['TOTAL_ENERGY']['Value']){
                    echo " -> Nhỏ hơn lần gửi trước";
                }
                echo "</div>";       
                $last=$item['DATA']['TOTAL_ENERGY']['Value'];
            }
        }else{
            echo "NULL";
        }
    }
}
