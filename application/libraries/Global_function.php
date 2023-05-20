<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/14/2018
 * Time: 2:39 PM
 */
class Global_function {
    public function fixSql($string)
    {
        $string = str_ireplace("&*#39;","",$string);
        $string = str_ireplace("&quot;","",$string);
        $string = str_ireplace("'","",$string);
        $string = str_ireplace("\"","",$string);
        $string = str_ireplace(" and ","",$string);
        $string = str_ireplace(" OR ","",$string);
        $string = str_ireplace(" or ","",$string);
        $string = str_ireplace(" union ","",$string);
        $string = str_ireplace("\\","",$string);
        $string = str_ireplace("#","",$string);
        $string = str_ireplace("--","",$string);
        //	$string = sqlQuote($string);
        return $string;
    }

    public function fixXSS($string)
    {
        $string = str_ireplace("&*#39;","",$string);
        $string = str_ireplace("&quot;","",$string);
        $string = str_ireplace("'","",$string);
        $string = str_ireplace("\"","",$string);
        $string = str_ireplace("\\","",$string);
        $string = str_ireplace("<!--","",$string);
        //	$string = sqlQuote($string);
        return $string;
    }

    public function fixSql_arr($input_arr,$not_fix=NULL) {
        if(count($input_arr)>0){
            foreach ($input_arr as $key => $value) {
                if($not_fix!=NULL) {
                    foreach($not_fix as $key_not_fix=>$value_not_fix) {
                        if($key!=$value_not_fix) {
                            $input_arr[$key] = $this->fixSql($value);
                        }
                    }
                }
                else {
                    if(is_array($value)){
                        $input_arr[$key] = $this->fixSql_arr($value);
                    }else{
                        $input_arr[$key] = $this->fixSql($value);
                    }
                }
            }
            return $input_arr;
        }else{
            return $this->fixSql($input_arr);
        }
    }

    public function xml_to_array($result)
    {
        $xml = simplexml_load_string($result->any);
        $arr = (array)$xml;
        $arr=(array)$arr['NewDataSet'];
        $arr=$arr['Table'];

        if(is_array($arr))
        {
            foreach($arr as $item)
            {
                $kq[]=(array)$item;
            }
        }
        else
        {
            $kq[]=(array)($arr);
        }
        return $kq;
    }

    public function format_thoigian($time,$str_format) {
        if($time!=NULL) {
            $timestamp=strtotime($time);
            //var_dump($timestamp);
            $kq=gmdate($str_format,$timestamp);
            return $kq;
        }
        else
            return "";
    }

    function str_to_strDate($str,$format){
        if($str!=NULL)
            return date($format,strtotime($str));
        else{
            return "";
        }
    }


    function implode_array2string($mang) {
        return $chuoi = "'" . implode("','", $mang) . "'";
    }

    // Tính tổng số ngày trong tháng
    function songay_trongthang($thang=NULL, $nam=NULL) {
        if (($thang==NULL) && ($nam==NULL)) {
            $thang = gmdate('m',time());
            $nam = gmdate('Y',time());
        } else {
            $thang = (int)$thang;
            $nam = (int)$nam;
        }

        switch($thang) {
            case '1':case '3': case '5': case '7': case '8': case '10': case '12':
            return 31;
            break;

            case '4': case '6': case '6': case '9': case '11':
            return 30;
            break;

            case '2':
                if((($nam%4==0)&&($nam%100!=0))||($nam%400==0))
                    return 29;
                else
                    return 28;
                break;
        }
    }

    // tính khoảng thời gian giữa 2 datetime: trả về số phút
    //echo khoang_thoigian('02/04/2015 16:43:00', '02/04/2015 16:44:00');
    function khoang_thoigian($datetime1, $datetime2) {
        //var_dump($datetime2);
        $datetime1 = strtotime($datetime1); // số giây tính từ 01/01/1970 00:00:00 đến ngày nhập vào
        $datetime2 = strtotime($datetime2); // số giây tính từ 01/01/1970 00:00:00 đến ngày nhập vào
        $dateDiff = abs($datetime1 - $datetime2); // lấy giá trị tuyệt đối (k có số âm)
        return floor($dateDiff/60); // floor: làm tròn khi chia 60 giây để có số phút
    }


    // Trường => function loai bo dau Tieng Viet
    function vn_str_filter($str){

        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

        );

        foreach($unicode as $nonUnicode=>$uni){
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }

        return $str;
    }

    // Trường => chuyen doi d/m/Y H:i:s thành Y-m-d H:i:s
    function convert_strDateTime_format_from_vi_to_en($str){
        $ngay = substr($str,0,2);
        $thang = substr($str,3,2);
        $nam = substr($str,6,4);
        $giophutgiay = substr($str,11);
        $format = $nam."-".$thang."-".$ngay." ".$giophutgiay;
        return $format;
    }

    function convert_to_viDay($str){
        switch($str){
            case 'Sun':
                return 'Chủ Nhật';
                break;
            case 'Mon':
                return 'Thứ Hai';
                break;
            case 'Tue':
                return 'Thứ Ba';
                break;
            case 'Wed':
                return 'Thứ Tư';
                break;
            case 'Thu':
                return 'Thứ Năm';
                break;
            case 'Fri':
                return 'Thứ Sáu';
                break;
            case 'Sat':
                return 'Thứ Bảy';
                break;
        }
    }

    function layKhoangThoiGianTheoQuyNam($quy,$nam){
        switch($quy){
            case 1:
                $tuNgay = '01/01/'.$nam;
                $soNgayTrongThangCuoi=$this->songay_trongthang(3,$nam);
                $denNgay = $soNgayTrongThangCuoi.'/03/'.$nam;
                break;
            case 2:
                $tuNgay = '01/04/'.$nam;
                $soNgayTrongThangCuoi=$this->songay_trongthang(6,$nam);
                $denNgay = $soNgayTrongThangCuoi.'/06/'.$nam;
                break;
            case 3:
                $tuNgay = '01/07/'.$nam;
                $soNgayTrongThangCuoi=$this->songay_trongthang(9,$nam);
                $denNgay = $soNgayTrongThangCuoi.'/09/'.$nam;
                break;
            case 4:
                $tuNgay = '01/10/'.$nam;
                $soNgayTrongThangCuoi=$this->songay_trongthang(12,$nam);
                $denNgay = $soNgayTrongThangCuoi.'/12/'.$nam;
                break;
        }
        $arrKhoangThoiGian = array(
            'tuNgay' => $tuNgay,
            'denNgay'=> $denNgay
        );
        return $arrKhoangThoiGian;
    }

    // $ngay1 = "d/m/Y H:i:s";
    // $ngay2 = "d/m/Y H:i:s";
    // return số giây ngay1 - ngay2
    function hieu_thoigian($ngay1,$ngay2){
        $ngay1 = strtotime($this->convert_strDateTime_format_from_vi_to_en($ngay1));
        $ngay2 = strtotime($this->convert_strDateTime_format_from_vi_to_en($ngay2));
        $hieuso_giay = $ngay1 - $ngay2;
        return $hieuso_giay;
    }

    public function hash_password($password){
        $password = md5(sha1(md5($password.STRHASHPASS)));
        return $password;
    }

    // check chuỗi ngày hợp lệ
    public function isChuoiNgayThangNam($str){
        $str = substr($str,0,10);
        if(strlen($str)!=10){
            return false;
        }

        $arr = explode("/", $str);
        if(!is_array($arr)){
            return false;
        }
        if(count($arr)!=3){
            return false;
        }

        $d = $arr[0];
        $m = $arr[1];
        $y = $arr[2];
        if(strlen($d)!=2 || strlen($m)!=2 || strlen($y)!=4){
            return false;
        }
    
        if($d > 31 || $d == 0|| $m > 12 || $m == 0 ){
            return false;
        }

        return true;
    }

    public function array_sort($array, $on, $order=SORT_ASC){
        $new_array = array();
        $sortable_array = array();
        if(is_array($array)){
            if (count($array) > 0) {
                foreach ($array as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $k2 => $v2) {
                            if ($k2 == $on) {
                                $sortable_array[$k] = $v2;
                            }
                        }
                    } else {
                        $sortable_array[$k] = $v;
                    }
                }
    
                switch ($order) {
                    case SORT_ASC:
                        asort($sortable_array);
                    break;
                    case SORT_DESC:
                        arsort($sortable_array);
                    break;
                }
    
                foreach ($sortable_array as $k => $v) {
                    $new_array[$k] = $array[$k];
                }
            }
        }
        
        return $new_array;
    }

    public function formatSoTien($tien){
        if($tien>10000000){
            $tienTreu=$tien/1000000;
            // $tienTreu=$tien;
            return number_format($tienTreu,2)." triệu ";
        }else{
            return number_format($tien,0);
        }
    }

    // public function formatValue($num,$maxDeci=0){
    //     $phanNguyen = (int) $num;
    //     $phanThapPhan = (int)((int)(($num - $phanNguyen)*10*$maxDeci)/(10*$maxDeci));

    //     if($phanThapPhan>0){
    //         return number_format($phanNguyen,0).".".$phanThapPhan;
    //     }else{
    //         return number_format($phanNguyen,0);
    //     }

        
    // }
}