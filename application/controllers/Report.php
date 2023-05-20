<?php
/**
 * Created by PhpStorm.
 * User: Ly Xuan Truong
 * Date: 06/11/2018
 * Time: 3:09 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/* @property global_function $global_function
 * @property M_report $M_report
 * @property Mglobal $Mglobal
 */
class Report extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('html');
        $this->load->helper('url');
        $this->load->Model("M_report");
        $this->load->library("global_function");
        if(!isset($_SESSION[LOGIN])){
            $_SESSION[LOGIN] = NULL;
        }

        if($_SESSION[LOGIN]==NULL){
            header('location:'.base_url('login'));
        }
    }

    public function index(){
        $data_view['template'] = "report/report";
        $data_view['title'] = "Báo cáo số liệu";
        $data_view['iconTitle'] = "cil-people";
        $data_view['data'] = NULL;
        $data_view['data']['thongTinCty'] = $_SESSION[LOGIN]['tenCongTy'];
        $data_view['data']['loaiTaiKhoan'] = $_SESSION[LOGIN]['loaiTaiKhoan'];

        // $data_view['data']['list_device'] = $this->M_report->get_list_device($username);

        $this->load->view('layout/layout',$data_view);
    }

    public function get_data_report(){
        $user = $this->global_function->fixSql($this->input->post('user'));
        $idKhuVuc = $this->global_function->fixSql($this->input->post('idKhuVuc'));
        $idThietBi = $this->global_function->fixSql($this->input->post('idThietBi'));
        $from = $this->global_function->fixSql($this->input->post('from'));
        $to = $this->global_function->fixSql($this->input->post('to'));

        $start = $this->global_function->fixSql($this->input->post('start'));
        $length = $this->global_function->fixSql($this->input->post('length'));
        $search = $this->global_function->fixSQL_arr($this->input->post('search'));


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
                  
                }
                
            break;
        }
    }

    public function download_report(){
        $username = $_SESSION[LOGIN]['username'];
        $device_id = $this->global_function->fixSql($this->input->post('device_id'));
        $from = $this->global_function->fixSql($this->input->post('from'));
        $to = $this->global_function->fixSql($this->input->post('to'));

        if($device_id=="") {
            $result = array(
                'state' => 'error',
                'alert' => 'Send your device ID !',
            );
        }else{
            $data_temp = $this->M_report->getDataReport($username,$device_id,$from,$to);

            /** Include PHPExcel */
            require_once 'public/lib/ExcelClasses/PHPExcel.php';

            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Manager")
                ->setLastModifiedBy("Manager")
                ->setTitle("From ".$from.' To '.$to)
                ->setSubject("Report")
                ->setDescription("History")
                ->setKeywords("")
                ->setCategory("Data");


            // Add some data
            $objPHPExcel->setActiveSheetIndex(0);
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('DATA');
            $objPHPExcel->getActiveSheet()->setCellValue('A1',"No");
            $objPHPExcel->getActiveSheet()->setCellValue('B1',"Temperature (°C)");
            $objPHPExcel->getActiveSheet()->setCellValue('C1',"Humidity (%)");
            $objPHPExcel->getActiveSheet()->setCellValue('D1',"Time");

            if($data_temp!=NULL){
                foreach($data_temp as $index=>$item){
                    $row = $index+2;
                    $objPHPExcel->getActiveSheet()->setCellValue("A".$row,($index+1));
                    $objPHPExcel->getActiveSheet()->setCellValue("B".$row,$item['temperature']);
                    $objPHPExcel->getActiveSheet()->setCellValue("C".$row,$item['humidity']);
                    $objPHPExcel->getActiveSheet()->setCellValue("D".$row,$item['time']);

                }
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="export_data.xlsx"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit();
        }
    }

}
