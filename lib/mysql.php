<?php
class Mysql{

    private $conn;

    private $db;
        
    public function __construct($host, $user, $pass, $database){
        $this->conn = mysql_connect($host, $user, $pass) or die('Con not connenct to database');
        $this->db = $database;
        $this->select_db($this->db, $this->conn);
        // 设置字符编码
        mysql_query("SET NAMES utf8", $this->conn);
    }
        
    public function __destruct(){
        mysql_close($this->conn);
    }

    public function select_db($database) {
        mysql_select_db($database);
    }

    public function execute($sql) {
        $this->select_db($this->db);
        mysql_query($sql);
    }
                
    /**
     * 获取一个结果集
     * @param string $sql
     * @param string $type
     * @return array
     */
    public function selectRecords($sql){
        $this->select_db($this->db);
        $result = mysql_query($sql);
        $rows = array();
        while($value = mysql_fetch_array($result, MYSQL_ASSOC))
            $rows[] = $value;
        return $rows;
    }
        
    /**
     * 插入新数据
     * @param string $table
     * @param array $args key代表字段，value代表插入的值
     */
    public function insertRecords($table, Array $args){
        foreach($args as $k => $v) $args[$k] = (is_numeric($v) && intval($v) == $v)? $v : "'".$v."'";
        $sql = "INSERT INTO `$table` (`".implode('`, `',array_keys($args))."`) VALUES (".implode(',', $args).")";
        $this->select_db($this->db);
        mysql_query($sql);
        return mysql_insert_id();
    }
        
    /**
     * 删除记录
     * @param string $table 表名
     * @param string $condition 删除条件
     * @param string $limit 删除多少条
     */
    public function deleteRecords($table, $condition, $limit = ''){
        $limit = (empty($limit))? '' : ' LIMIT '.$limit;
        $sql = 'DELETE FROM `'.$table.'` WHERE '.$condition.$limit;
        $this->select_db($this->db);
        mysql_query($sql);
    }
        
    /**
    * 更新记录
    * @param string $table 表名
    * @param string $condition 更新条件
    * @param array $changes 更新的值，数组类型，key对应字段，value对应值
    */
    public function updateRecords($table, $changes, $condition = '') {
        $table = '`'.$table.'`';
        $sql = 'UPDATE '.$table.' SET ';
        foreach($changes as $k => $v) {
                $v = (is_numeric($v) && intval($v) == $v)? $v.',' : "'".$v."',";
                $sql .= '`'.$k.'` = '.$v;
        }
        $sql = substr($sql, 0, -1);
        if(!empty($condition)) {
                $sql .= ' WHERE '.$condition;
        }
        $this->select_db($this->db);
        mysql_query($sql);
    }

}// Database class file end
