<?php
/**
 * Created by PhpStorm.
 * User: Ly Xuan Truong
 * Date: 06/11/2018
 * Time: 3:09 PM
 */
class M_report extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_list_device($username){
        $sql="select * from user_device where state='1' and username='{$username}' ";
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function getDataReport($username,$device_id,$from,$to){
        $sql="select * from his_devices 
              where 
              state ='1' and username='{$username}' and device_id='{$device_id}' 
              and DATE(time) between STR_TO_DATE('".$from."', '%d/%m/%Y') and STR_TO_DATE('".$to."', '%d/%m/%Y')
              order by time  ";
//echo $sql;
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function get_device_info($username,$device_id){
        $sql="select * from user_device where state='1' and username='{$username}' and id_device='{$device_id}' ";
        $query = $this->db->query($sql);
        $rs = $query->row_array();
        return $rs;
    }



    /*
     *
     *
     *
     * ALTER TABLE `user_device` ADD `location` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL AFTER `device_name`;
     * */

}