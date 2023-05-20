<?php
/**
 * Created by PhpStorm.
 * User: Ly Xuan Truong
 * Date: 03/08/2020
 * Time: 3:09 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/** 
 * @property global_function $global_function
 * @property M_api $M_api
 * @property Mglobal $Mglobal
 */
class Api extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('html');
        $this->load->helper('url');
        $this->load->Model("M_api");
        $this->load->library("global_function");
        if(!isset($_SESSION[LOGIN])){
            $_SESSION[LOGIN] = NULL;
        }
    }

    public function test(){
        $deviceID= "DEVICEID_TEST_08";
        $timeStamp= gmdate('d/m/Y H:i:s',time()+7*3600 );
        $timeSend = gmdate('d/m/Y H:i:s',time()+7*3600 + 60 );
        $PAC= '35';
        $data = array(
            'DATA' => array(
                'DAY_ENERGY' => array(
                    'Unit' => 'kWh',
                    'Value' => '20173.24'
                ),
                'MONTH_ENERGY' => array(
                    'Unit' => 'null',
                    'Value' => 'null'
                ),
                'YEAR_ENERGY' => array(
                    'Unit' => 'null',
                    'Value' => 'null'
                ),
                'TOTAL_ENERGY' => array(
                    'Unit' => 'kWh',
                    'Value' => '123557.104'
                ),
                'PAC' => array(
                    'Unit' => 'W',
                    'Value' => $PAC
                ),
                'NUM_AC' => '3', // số pha
                'AC_A'=> array(
                    'UAC' => array(
                        'Unit' => 'W',
                        'Value' => '153.1'
                    ),
                    'IAC' => array(
                        'Unit' => 'W',
                        'Value' => '153.1'
                    ),
                ),
                'AC_B'=> array(
                    'UAC' => array(
                        'Unit' => 'W',
                        'Value' => '153.1'
                    ),
                    'IAC' => array(
                        'Unit' => 'W',
                        'Value' => '153.1'
                    ),
                ),
                'AC_C'=> array(
                    'UAC' => array(
                        'Unit' => 'W',
                        'Value' => '153.1'
                    ),
                    'IAC' => array(
                        'Unit' => 'W',
                        'Value' => '153.1'
                    ),
                ),

                "FREQUENCY_AC" => array(
					"Unit" => "Hz",
					"Value"=>"49.54"
				),
                "INTERNAL_TEMP" => array(
					"Unit" => "Do C",
					"Value" => "55.6"
                ),
                'PV_STRING' => array(
                    array(
                        'ID_STRING' => 'String1',
                        'PV_UDC' => array(
                            "Unit" => "V",
					        "Value" => "550.5"
                        ),
                        'PV_IDC' => array(
                            "Unit" => "A",
					        "Value" => "10.3"
                        )
                    ),
                    array(
                        'ID_STRING' => 'String2',
                        'PV_UDC' => array(
                            "Unit" => "V",
					        "Value" => "550.5"
                        ),
                        'PV_IDC' => array(
                            "Unit" => "A",
					        "Value" => "9.5"
                        )
                    ),
                )
                
            ),
            'HEAD' => array(
                'DEVICE' => array(
                    'DeviceID' => $deviceID,
                    "SERI"=> "ABC12345678",
                    "DeviceClass" => "Inverter",
					"Manufacturer" => "ZERVER SOLAR",
					"Model" => "acv123",
					"Device_Address" => "1234567"
                ),
                'Status' => array(
                    "Code"=> 0,
					"Reason"=> "",
					"UserMessage"=> ""
                ),
                "GateWay" => array(
                    "Status" => "normal",
                    "LastError" => "no error",
                    "Firmware" => "1.2",
                    "Model" => "ABC"
                ),
            ),
            'Timestamp' => $timeStamp,
            'TimeSend' => $timeSend,
            'TOKEN' => $this->create_token(array(
                'DeviceID' => $deviceID,
                "PAC"=> $PAC,
                "Timestamp" => $timeStamp,
                "TimeSend" => $timeSend,
            )),
        );

        echo json_encode($data);
    }
    public function index(){
        if($_SESSION[LOGIN]==NULL){
            header('location:'.base_url('login')); exit();
        }
        if($_SESSION[LOGIN]['loaiTaiKhoan']!='quantri'){
            header('location:'.base_url('login')); exit();
        }

        $data_view['template'] = "api/api";
        $data_view['title'] = "API Document";
        $data_view['iconTitle'] = "cil-3d";
        $data_view['data'] = NULL;
        $data_view['data']['thongTinCty'] = $_SESSION[LOGIN]['tenCongTy'];
        $data_view['data']['loaiTaiKhoan'] = $_SESSION[LOGIN]['loaiTaiKhoan'];

        $data_view['data']['currentTime']=gmdate('d/m/Y H:i:s',time()+7*3600);
        $time_convert=strTotime($this->global_function->convert_strDateTime_format_from_vi_to_en($data_view['data']['currentTime']));
        $data_view['data']['currentTimeConvert'] = gmdate('d/m/Y H:i:s',$time_convert);
        $this->load->view('layout/layout',$data_view);
    }

	public function post_data(){
        ini_set('memory_limit', '2048M');
        header("Content-type: application/json; charset=utf-8");
        $json = file_get_contents('php://input');
		$ip = $this->input->ip_address();		
		log_message('debug', "start----> insert ".$ip."--->".date("Y-m-d H:i:s"));
        if($json==NULL){
            $result = array(
                'state' => 'error',
                'alert' => "ERROR: NOT POST DATA!"
            );
            echo json_encode($result); exit();
        }
        //log_message('debug', $json);
        $postData = json_decode($json,true);
        if(!is_array($postData)){
            echo json_encode($this->responseDataFalse()); exit();
        }

        $data = $this->global_function->fixSql_arr($postData);

        // check isset all ost data
        if(!$this->checkIssetPostDATA($data)){
            echo json_encode($this->responseDataFalse()); exit();
        }

        // Kiểm tra TOKEN
        $dataToken = array(
            'DeviceID' => $data['HEAD']['DEVICE']['DeviceID'],
            "PAC"=> $data['DATA']['PAC']['Value'],
            "Timestamp" => $data['Timestamp'],
            "TimeSend" => $data['TimeSend'],

        );
        $check = $this->check_token($dataToken,$data['TOKEN']);
        if($check==false){
            $result = array(
                'state' => 'error',
                'alert' => "ERROR: TOKEN FALSE!"
            );
            echo json_encode($result); exit();
        }

        // kiểm tra TimeSend
        if(!$this->timeSendAccept($data['TimeSend'])){
            $result = array(
                'state' => 'error',
                'alert' => "ERROR: TOKEN IS EXPIRED!"
            );
            echo json_encode($result); exit();
        }

        // xử lý kiểm tra dữ liệu có tháng hay không? Nếu không có số liệu tháng thì tính tháng dựa trên số liệu hiện có

        // Kiểm tra xem deiveID (MAC) có hợp lệ hay không?
        $post_DeviceID = $data['HEAD']['DEVICE']['DeviceID'];
        $thongTinThietBi = $this->M_api->thongTinThietBiFromDeviceID($post_DeviceID);
        if($thongTinThietBi==NULL){
            echo json_encode($this->responseDataFalse()); exit();
        }

        // kiểm tra tín hiệu sau cùng phải lớn hơn tín hiệu trước đó
        if(!$this->checkTinHieuSauCung_hople($data,$thongTinThietBi['taiKhoanKhachHang'],$thongTinThietBi['idKhuVucLapDat'],$thongTinThietBi['idThietBi'])){
            $result = array(
                'state' => 'error',
                'alert' => "ERROR: TOTAL_ENERGY INVALID!"
            );
            echo json_encode($result); exit();
        }

        $data=$this->tinhSoLieuThangVaNamIfNull($data,$thongTinThietBi['taiKhoanKhachHang'],$thongTinThietBi['idKhuVucLapDat'],$thongTinThietBi['idThietBi']);


        $ngayTao=gmdate('d/m/Y H:i:s',time()+7*3600);

        // Kiểm tra HEAD có gì mới không? nếu có thì cập nhật  HEAD 
        $jsonHEAD = json_encode($data['HEAD']);
        if($thongTinThietBi['HEAD'] != $jsonHEAD){
            // cập nhật HEAD cho thiết bị

            $this->M_api->capNhatHeadChoThietBi($post_DeviceID,$jsonHEAD,$data['Timestamp']);
        }
        
        // Kiểm tra Timestamp đã ghi nhận tín hiệu chưa?
        // Nếu chưa ghi nhận thì insert ghi nhận
        // Nếu đã ghi nhận rồi thì update ghi nhận cũ. Lưu trữ tín hiệu cũ dạng json vào cột duLieuTheoGio
        $tinHieuTheoNgay = $this->M_api->layTinHieuTheoNgay($post_DeviceID,$thongTinThietBi['taiKhoanKhachHang'],$data['Timestamp']);
        $data['TimestampInt']=strtotime($this->global_function->convert_strDateTime_format_from_vi_to_en($data['Timestamp']));
        if($tinHieuTheoNgay == NULL){
            // chưa ghi nhận
            // Ghi nhận tin hiệu tổng
            
            $duLieuTheoGio=NULL;
            $duLieuTheoGio[] = $data;
            $duLieuTheoGio_json = json_encode($duLieuTheoGio);

            $duLieuTheoGioTomTat=NULL;
            $duLieuTheoGioTomTat[]=$this->tomTatDuLieuNhan($data);
            $duLieuTheoGioTomTat_json = json_encode($duLieuTheoGioTomTat);

            $insertID = $this->M_api->ghiNhanTinHieuTong($post_DeviceID,$thongTinThietBi['taiKhoanKhachHang'],$thongTinThietBi['idKhuVucLapDat'],$data['DATA'],$duLieuTheoGio_json,$duLieuTheoGioTomTat_json,$data['Timestamp'],$ngayTao);
            $this->M_api->ghiNhanLogTinHieuTong($post_DeviceID,$thongTinThietBi['taiKhoanKhachHang'],$thongTinThietBi['idKhuVucLapDat'],$insertID,$duLieuTheoGio_json,$data['Timestamp']);
            // Ghi nhận tín hiệu String
            if($data['DATA']['PV_STRING']!=NULL){
                foreach($data['DATA']['PV_STRING'] as $dataOneString){
                    $this->M_api->ghiNhanTinHieuString($post_DeviceID,$insertID,$dataOneString,$data['Timestamp'],$ngayTao);
                }
            }
        }else{
            // đã ghi nhận
            // lấy log tín hiệu
            $log = $this->M_api->layLogTinHieuTong($post_DeviceID,$thongTinThietBi['taiKhoanKhachHang'],$thongTinThietBi['idKhuVucLapDat'],$tinHieuTheoNgay['id']);
            if($log==NULL){ // xử lý lấy dữ liệu cũ trong giai đoạn chuyển đổi
                $log['DATA']=$tinHieuTheoNgay['duLieuTheoGio'];
                $this->M_api->ghiNhanLogTinHieuTong($post_DeviceID,$thongTinThietBi['taiKhoanKhachHang'],$thongTinThietBi['idKhuVucLapDat'],$tinHieuTheoNgay['id'],$tinHieuTheoNgay['duLieuTheoGio'],$data['Timestamp']);
            }
            // update tinhieu_tong
            $duLieuTheoGioMoi=json_decode($log['DATA'],true);
            $duLieuTheoGioMoi[] = $data;
            $duLieuTheoGioMoi_json = json_encode($duLieuTheoGioMoi);

            $duLieuTheoGioMoiTomTat=json_decode($tinHieuTheoNgay['duLieuTheoGioTomTat'],true);
            $duLieuTheoGioMoiTomTat[]=$this->tomTatDuLieuNhan($data);
            $duLieuTheoGioMoiTomTat_json = json_encode($duLieuTheoGioMoiTomTat);
          
            $this->M_api->updateTinHieuTong($tinHieuTheoNgay['id'],$data['DATA'],$duLieuTheoGioMoi_json,$duLieuTheoGioMoiTomTat_json,$data['Timestamp'],$ngayTao);
            // xóa STRING cũ
            $this->M_api->xoaStringTheoIdTinHieu($post_DeviceID,$tinHieuTheoNgay['id']);
            // Insert string mới
            if($data['DATA']['PV_STRING']!=NULL){
                foreach($data['DATA']['PV_STRING'] as $dataOneString){
                    $this->M_api->ghiNhanTinHieuString($post_DeviceID,$tinHieuTheoNgay['id'],$dataOneString,$data['Timestamp'],$ngayTao);
                }
            }
        }
        
        // -------------------------- chốt số liệu ra bảng PAC------------------------
        $this->chotSoLieuRaBangPAC($thongTinThietBi,$data['Timestamp']);

        // ----------------------- // chốt số liệu ra bảng PAC------------------------
        $result = array(
            'state' => 'success',
            'alert' => "POST DATA SUCCESS!"
        );
		log_message('debug', "done----> insert ".$ip."--->".date("Y-m-d H:i:s"));
        echo json_encode($result);
        exit();
    }

    private function responseDataFalse(){
        $result = array(
            'state' => 'error',
            'alert' => "ERROR: POST DATA FALSE!"
        );
        return $result;
    }

    private function checkIssetPostDATA($POSTDATA){
        // HEAD
        if(!isset($POSTDATA['HEAD']['DEVICE']['DeviceID'])){
            return false;
        }

        // Timestamp
        if(!isset($POSTDATA['Timestamp'])){
            return false;
        }
        if(!$this->global_function->isChuoiNgayThangNam($POSTDATA['Timestamp'])){
            return false;
        }

        // TimeSend
        if(!isset($POSTDATA['TimeSend'])){
            return false;
        }
        if(!$this->global_function->isChuoiNgayThangNam($POSTDATA['TimeSend'])){
            return false;
        }

        // TOKEN
        if(!isset($POSTDATA['TOKEN'])){
            return false;
        }
        if($POSTDATA['TOKEN']==""){
            return false;
        }

        // DATA 
        $data = $POSTDATA['DATA'];
        if(!isset($data['DAY_ENERGY']['Unit'])){
            return false;
        }
        
        if(!isset($data['DAY_ENERGY']['Value'])){
            return false;
        }
        if(!isset($data['MONTH_ENERGY']['Unit'])){
            return false;
        }
        if(!isset($data['MONTH_ENERGY']['Value'])){
            return false;
        }
        if(!isset($data['YEAR_ENERGY']['Unit'])){
            return false;
        }
        if(!isset($data['YEAR_ENERGY']['Value'])){
            return false;
        }
        if(!isset($data['TOTAL_ENERGY']['Unit'])){
            return false;
        }
        if(!isset($data['TOTAL_ENERGY']['Value'])){
            return false;
        }
        if(!isset($data['PAC']['Unit'])){
            return false;
        }
        if(!isset($data['PAC']['Value'])){
            return false;
        }
        
        if(!isset($data['NUM_AC'])){
            return false;
        }
        if(!isset($data['AC_A']['UAC']['Unit'])){
            return false;
        }
        if(!isset($data['AC_A']['UAC']['Value'])){
            return false;
        }
        if(!isset($data['AC_A']['IAC']['Unit'])){
            return false;
        }
        if(!isset($data['AC_A']['IAC']['Value'])){
            return false;
        }
        
        if(!isset($data['AC_B']['UAC']['Unit'])){
            return false;
        }
        if(!isset($data['AC_B']['UAC']['Value'])){
            return false;
        }
        if(!isset($data['AC_B']['IAC']['Unit'])){
            return false;
        }
        if(!isset($data['AC_B']['IAC']['Value'])){
            return false;
        }
        
        if(!isset($data['AC_C']['UAC']['Unit'])){
            return false;
        }
        if(!isset($data['AC_C']['UAC']['Value'])){
            return false;
        }
        if(!isset($data['AC_C']['IAC']['Unit'])){
            return false;
        }
        if(!isset($data['AC_C']['IAC']['Value'])){
            return false;
        }
        
        if(!isset($data['FREQUENCY_AC']['Unit'])){
            return false;
        }
        if(!isset($data['FREQUENCY_AC']['Value'])){
            return false;
        }

        if(!isset($data['INTERNAL_TEMP']['Unit'])){
            return false;
        }
        if(!isset($data['INTERNAL_TEMP']['Value'])){
            return false;
        }
        if(!isset($data['PV_STRING'])){
            return false;
        }

        if($data['PV_STRING']!=NULL){
            foreach($data['PV_STRING'] as $item){
                if(!isset($item['ID_STRING'])){
                    return false;
                }
                if(!isset($item['PV_UDC']['Unit'])){
                    return false;
                }
                if(!isset($item['PV_UDC']['Value'])){
                    return false;
                }
                if(!isset($item['PV_IDC']['Unit'])){
                    return false;
                }
                if(!isset($item['PV_IDC']['Value'])){
                    return false;
                }
            }
        }

        return true;

    }
    private function check_token($data,$token){
        if($data!=NULL || $token ){
            $hash = $this->create_token($data);
            if($hash==$token){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    private function checkTinHieuSauCung_hople($dataPost,$taiKhoanKhachHang,$idKhuVucLapDat,$idThietBi){
        $last_data=$this->M_api->layDuLieuCuoiCung($taiKhoanKhachHang,$idKhuVucLapDat,$idThietBi);
        if($last_data==NULL){
            return true;
        }else{
            $data = $dataPost['DATA'];
            
            if(strtoupper($data['TOTAL_ENERGY']['Unit'])==strtoupper($last_data['TOTAL_ENERGY_Unit'])){
                if($data['TOTAL_ENERGY']['Value'] < $last_data['TOTAL_ENERGY_Value']){
                    return false;
                }else{
                    return true;
                }
            }else{
                // đơn vị không khớp nên phải đổi đơn vị
                if(strtoupper($data['TOTAL_ENERGY']['Unit'])=="KWH"){
                    $data['TOTAL_ENERGY']['Unit']="MWH";
                    $data['TOTAL_ENERGY']['Value']=$data['TOTAL_ENERGY']['Value']/1000;
                }

                if(strtoupper($last_data['TOTAL_ENERGY_Unit'])=="KWH"){
                    $last_data['TOTAL_ENERGY_Unit']="MWH";
                    $last_data['TOTAL_ENERGY_Value']=$last_data['TOTAL_ENERGY_Value']/1000;
                }

                if($data['TOTAL_ENERGY']['Value'] < $last_data['TOTAL_ENERGY_Value']){
                    return false;
                }else{
                    return true;
                }
            }
        }
    }


    private function create_token($data){
        $str_hash = "";
        if($data!=NULL){
            foreach($data as $key=>$value){
                $str_hash .= $key."=".$value."|";
            }
            $str_hash .=  SECRECT_KEY;
            return $hash = md5(sha1(base64_encode($str_hash)));
        }
    }
    private function timeSendAccept($timeSend){
        $intSend = strtotime($this->global_function->convert_strDateTime_format_from_vi_to_en($timeSend));
        $intNow = time()+7*3600;

        $quota = 30*60; // 30 phút
        if(abs($intSend - $intNow) > $quota){
            return false;
        }else{
            return true;
        }
    }

    private function tinhSoLieuThangVaNamIfNull($dataPost,$taiKhoanKhachHang,$idKhuVucLapDat,$idThietBi){
        // kiểm tra điều kiện tính số liệu tháng 
        if($dataPost['DATA']['MONTH_ENERGY']['Value']==="null" && $dataPost['DATA']['MONTH_ENERGY']['Unit']==="null"){   
            $year = substr($dataPost['Timestamp'],6,4);
            $month = substr($dataPost['Timestamp'],3,2);
            $DataDay=$this->M_api->layDuLieuCuoiCungCuaNgayTruoc($taiKhoanKhachHang,$idKhuVucLapDat,$idThietBi,$year,$month,$dataPost['Timestamp']);
            if($DataDay==NULL){ // nếu lần gửi dữ liệu đầu tiên trong tháng thì sẽ có $DataDay = NULL
                $dataPost['DATA']['MONTH_ENERGY']=$dataPost['DATA']['DAY_ENERGY'];
            }else{
                // đổi đơn vị
                if(strtoupper($dataPost['DATA']['DAY_ENERGY']['Unit'])=='KWH'){
                    $day_value = $dataPost['DATA']['DAY_ENERGY']['Value']/1000;
                }else{
                    $day_value = $dataPost['DATA']['DAY_ENERGY']['Value'];
                }

                $month_value=0;
                if(strtoupper($DataDay['MONTH_ENERGY_Unit'])=='KWH'){
                    $month_value = $DataDay['MONTH_ENERGY_Value']/1000;
                }else{
                    $month_value = $DataDay['MONTH_ENERGY_Value'];
                }

                $month_value+=$day_value;

                if($month_value<1){
                    $month_value=$month_value*1000;
                    $donvi_month='kWh';
                }else{
                    $donvi_month='MWh';
                }

                $dataPost['DATA']['MONTH_ENERGY']=array(
                    'Unit'=>$donvi_month,
                    'Value'=>$month_value,
                );
            }
        }

        // kiểm tra diều kiện tính số liệu năm
        if($dataPost['DATA']['YEAR_ENERGY']['Value']==="null" && $dataPost['DATA']['YEAR_ENERGY']['Unit']==="null"){
            $year = substr($dataPost['Timestamp'],6,4);
            $DataMonth=$this->M_api->layDuLieuCuoiCungCuaNgayTruoc_khongXetThang($taiKhoanKhachHang,$idKhuVucLapDat,$idThietBi,$year,$dataPost['Timestamp']);

            if($DataMonth==NULL){ // nếu lần gửi dữ liệu đầu tiên trong năm thì sẽ có $DataMonth = NULL
                $dataPost['DATA']['YEAR_ENERGY']=$dataPost['DATA']['DAY_ENERGY'];
            }else{
                // đổi đơn vị
                if(strtoupper($dataPost['DATA']['DAY_ENERGY']['Unit'])=='KWH'){
                    $day_value = $dataPost['DATA']['DAY_ENERGY']['Value']/1000;
                }else{
                    $day_value = $dataPost['DATA']['DAY_ENERGY']['Value'];
                }
                $year_value=0;
                if(strtoupper($DataMonth['YEAR_ENERGY_Unit'])=='KWH'){
                    $year_value = $DataMonth['YEAR_ENERGY_Value']/1000;
                }else{
                    $year_value = $DataMonth['YEAR_ENERGY_Value'];
                }

                $year_value+=$day_value;

                if($year_value<1){
                    $year_value=$year_value*1000;
                    $donvi_year='kWh';
                }else{
                    $donvi_year='MWh';
                }

                $dataPost['DATA']['YEAR_ENERGY']=array(
                    'Unit'=>$donvi_year,
                    'Value'=>$year_value,
                );
            }
        }

        return $dataPost;
    }

    private function doc_create_token(){
        $data = array(
            'DeviceID' => "EH23-JU34-2343-W2EDH",
            'PAC' => "115.3",
            'Timestamp' => "05/08/2020 18:04:22",
            'TimeSend' => "05/08/2020 18:05:22",
        );

        if($data!=NULL){
            $str_hash="";
            foreach($data as $key=>$value){
                $str_hash .= $key."=".$value."|";
            }
            $str_hash .=  SECRECT_KEY;
            echo "String hash: ".$str_hash;
            echo "<br>";
            echo "Cach ma hoa: md5(sha1(base64_encode(\"".$str_hash."\")))";
            echo "<br>";
            $hash = md5(sha1(base64_encode($str_hash)));
            echo "Token: ".$hash;
        }else{
            echo "Không có dữ liệu";
        }

    }

    private function tomTatDuLieuNhan($data){
        return array(
            'DATA' => array(
                'PAC' => $data['DATA']['PAC'],
                // 'NUM_AC' => $data['DATA']['NUM_AC'],
                // 'AC_A' => $data['DATA']['AC_A'],
                // 'AC_B' => $data['DATA']['AC_B'],
                // 'AC_C' => $data['DATA']['AC_C'],
                // 'FREQUENCY_AC' => $data['DATA']['FREQUENCY_AC'],
                // 'INTERNAL_TEMP' => $data['DATA']['INTERNAL_TEMP'],
            ),
            'Timestamp' => $data['Timestamp'],
            'TimestampInt' => strtotime($this->global_function->convert_strDateTime_format_from_vi_to_en($data['Timestamp']))
        );
    }

    //--------------------------------------------------------------------------------------
    // nâng cấp hiệu năng
    private function getBlockThoiGian($ngayNhanTinHieu,$blockLen=10){
        if($ngayNhanTinHieu==NULL){
            $ngayNhanTinHieu= gmdate('d/m/Y H:i:s',time()+7*3600);
        }
        $ngay = substr($ngayNhanTinHieu,0,10);
        $gio = substr($ngayNhanTinHieu,11,2);
        $ngayGio = substr($ngayNhanTinHieu,0,13);
        $phut = substr($ngayNhanTinHieu,14,2);
        $block = (int) ($phut/$blockLen);
        $from = $block*$blockLen;
        if($from<10){
            $from = "0".$from;
        }

        $to = $from + $blockLen;
        if($to<10){
            $to = "0".$to;
        }

        if($to > 59){
            $to = 59;
        }

        return array(
            'ngay' => $ngay,
            'from' => $ngayGio.":".$from.":00",
            'to' => $ngayGio.":".$to.":00",
            'time' => $gio.":".$from,
        );
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

    private function tinHieuDauTienTrongKhoanThoiGian($listData,$from,$to){ 
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

    private function chotSoLieuRaBangPAC($thongTinThietBi,$Timestamp){
        $blockThoiGian  = $this->getBlockThoiGian($Timestamp);  
        $taiKhoanChinh = $thongTinThietBi['taiKhoanKhachHang'];
        $idKhuVuc = $thongTinThietBi['idKhuVucLapDat'];
      
        // lấy số liệu theo giờ của thiết bị
        $listTinHieuThietBiTrongNgay = $this->M_api->getdataTrongNgay($blockThoiGian['ngay'],$taiKhoanChinh,$idKhuVuc);
        $listTinHieuThietBiTrongNgay =$this->xoaTrungDataThietBi($listTinHieuThietBiTrongNgay);
        
        $tongPAC=0; // 1 khoản thời gian
        if($listTinHieuThietBiTrongNgay!=NULL){ // danh sách các thiết bị gửi dữ liệu về trong ngày, đã lọc theo max thời gian
            foreach($listTinHieuThietBiTrongNgay as $thietbi){
                $listData=json_decode($thietbi['duLieuTheoGioTomTat'],true);
                // var_dump($listData);
                $timestamp_from = strtotime($this->global_function->convert_strDateTime_format_from_vi_to_en($blockThoiGian['from']));
                $timestamp_to = strtotime($this->global_function->convert_strDateTime_format_from_vi_to_en($blockThoiGian['to']));
                $dataHour = $this->tinHieuDauTienTrongKhoanThoiGian($listData,$timestamp_from,$timestamp_to);
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

        $ketqua = array(
            'time' => $blockThoiGian['time'],
            'Value' => $tongPAC,
            'Unit' => 'KW',
        );

        // lấy dữ liệu đã ghi nhận
        $thongTinDaChot = $this->M_api->thongTinPAC_DaChot($taiKhoanChinh,$idKhuVuc,$Timestamp);
        if($thongTinDaChot == NULL){
            // chưa chốt => insert mới
            $dataDaChot[$ketqua['time']] = array(
                'Value' => $ketqua['Value'],
                'Unit'  => $ketqua['Unit'],
            );

            $this->M_api->insertChoSoLieuPAC($taiKhoanChinh,$idKhuVuc,$Timestamp,json_encode($dataDaChot));
        }else{
            // đã chốt => update
            $dataDaChot = json_decode($thongTinDaChot['DATA'],true);
            $dataDaChot[$ketqua['time']] = array(
                'Value' => $ketqua['Value'],
                'Unit'  => $ketqua['Unit'],
            );

            $this->M_api->updateChoSoLieuPAC($thongTinDaChot['id'],$Timestamp,json_encode($dataDaChot));     
        }

    }

    public function test_block(){
        $input = $this->input->post('ngay');
        $rs  = $this->getBlockThoiGian($input);
        echo json_encode($rs); exit();
    }
    //--------------------------------------------------------------------------------------

}

/*
String hash: DeviceID=EH23-JU34-2343-W2EDH|PAC=115.3|Timestamp=05/08/2020 18:04:22|TimeSend=05/08/2020 18:05:22|e386b3c7a0e24129384cf58eb18dbb97
Cach ma hoa: md5(sha1(base64_encode("DeviceID=EH23-JU34-2343-W2EDH|PAC=115.3|Timestamp=05/08/2020 18:04:22|TimeSend=05/08/2020 18:05:22|e386b3c7a0e24129384cf58eb18dbb97")))
Token: 3360a91a06af67b76e49c935a7389ab8
*/
