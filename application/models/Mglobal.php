<?php

class Mglobal extends CI_Model{
    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->library('global_function');
        ini_set('xdebug.max_nesting_level', 500);
    }

    public function getOneRowTableByKey($table,$key,$value){
        $table = $this->global_function->fixSQL($table);
        $key = $this->global_function->fixSQL($key);
        $value = $this->global_function->fixSQL($value);

        $sql = "select top 1 * from [".$table."] where [".$key."] = '".$value."' ";
        $query = $this->db->query($sql);
        $rs = $query->row_array();
        return $rs;
    }

    public function getArrayTable($table){
        $table = $this->global_function->fixSQL($table);

        $sql = "select * from [".$table."]";
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function getArrayTableOrder($table,$order){
        $table = $this->global_function->fixSQL($table);
        $order = $this->global_function->fixSQL($order);

        $sql = "select * from [".$table."] order by ".$order;
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function getArrayTableByKey($table,$key,$value){
        $table = $this->global_function->fixSQL($table);
        $key = $this->global_function->fixSQL($key);
        $value = $this->global_function->fixSQL($value);

        $sql = "select * from [".$table."] where [".$key."] = '".$value."' ";
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function getArrayTableByKeyOrder($table,$key,$value,$order){
        $table = $this->global_function->fixSQL($table);
        $key = $this->global_function->fixSQL($key);
        $value = $this->global_function->fixSQL($value);
        $order = $this->global_function->fixSQL($order);

        $sql = "select * from [".$table."] where [".$key."] = '".$value."' order by ".$order;
        $query = $this->db->query($sql);
        $rs = $query->result_array();
        return $rs;
    }

    public function getOneFieldTableByKey($table,$key,$value,$field){
        $table = $this->global_function->fixSQL($table);
        $key = $this->global_function->fixSQL($key);
        $value = $this->global_function->fixSQL($value);
        $field = $this->global_function->fixSQL($field);

        $sql = "select top 1 [".$field."] from [".$table."] where [".$key."] = '".$value."' ";
        $query = $this->db->query($sql);
        $rs = $query->row_array();
        return $rs[$field];
    }

    /**
     * @author thold
     * @param int $isHigh. isHigh == 1 la thiet bi cao the
     * @return array
     */
    public function getListTypeEdges($isHigh = 0){
        $sql="select * from [imit_elements_types] where ([is_high]=$isHigh or [is_all]=1) and [parent] is null order by [order]";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }

    /**
     * @author thold
     * get element node type 'nodes' single. not group, not sub
     * @param int $isHigh. isHigh == 1 la thiet bi cao the
     * @return array
     */
    public function getListTypeNodeSingle($isHigh = 0){
        $sql="select * from [imit_elements_types]
              where [group]='nodes'
              and ([is_high]=$isHigh or [is_all]=1)
              and (datalength([group_type])='0' OR [group_type] is null)
              and (datalength([types_sub])='0' OR [types_sub] is null) order by [order]";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }

    public function dsDonViTCT(){
        $sql = "SELECT [ten_donvi],[ma_dviqly]
                FROM [donvi_tatca]
                ORDER BY [stt] ASC ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

	public function dsDonVi(){
        $sql = "SELECT [ten_donvi],[ma_dviqly]
                FROM [donvi]
                WHERE SUBSTRING([ma_dviqly],1,2)='PE'
                ORDER BY [ma_dviqly] ASC ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function layLoaiMatDien(){
        $sql = "SELECT *
                FROM [loai_matdien]
                ORDER BY
                    CASE
                        WHEN [id_loaimatdien] = 2 THEN 1
                        WHEN [id_loaimatdien] = 3 THEN 2
                        WHEN [id_loaimatdien] = 4 THEN 3
                        WHEN [id_loaimatdien] = 1 THEN 4
                        WHEN [id_loaimatdien] = 5 THEN 5
                        WHEN [id_loaimatdien] = 6 THEN 6
                        WHEN [id_loaimatdien] = 7 THEN 7
                    END ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}

?>