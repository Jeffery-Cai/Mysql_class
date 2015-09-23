<?php
  /**
 * 数据库操作类
 */
  class Mysql
  {

    // 要查询的字段
    private $select = '*';

    // 要查询的表名
    private $from = '';

    // 要查询的条件
    private $where = '';

    // 要查询的排序
    private $orderby = '';

    // 限制记录数
    private $limit = '';

    /**
     * @param string $dbname 数据库名称
     * @param string $dbpwd 数据库密码
     * @param string $dbuser 数据库账号
     * @param string $hostname 数据库主机名
     * @param string $encoding 数据库编码
     */
    public function __construct($dbname, $dbpwd = '', $dbuser = 'root', $hostname = 'localhost', $encoding = 'utf8')
    {
        // 连接数据库。这里填写主机名、账号和密码去连接数据库。
        // 如果失败就终止脚本，并且输出'连接数据库失败'到页面。
      mysql_connect($hostname, $dbuser, $dbpwd) or die('连接数据库失败');

        // 为了防止乱码的情况，使用mysql_set_charset函数设置
        // 编码。
      mysql_set_charset($encoding);

        // 选择数据库。这里使用mysql_select_db函数选择数据库，
        // 如果找不到数据库，就终止脚本，并且输出“你选择的数据库
        // 不存在”到页面上。
      mysql_select_db($dbname) or die('你选择的数据库不存在');

    }

    /**
     * 重置属性值，防止两次操作时，属性值冲突
     */
    public function init()
    {
      $this->select = '*';
      $this->from = '';
      $this->where = '';
      $this->orderby = '';
      $this->limit = '';
    }

    /**
     * 设置from
     * @param string $from 设置表名
     * @return $this
     */
    public function from($from)
    {
      $this->from = $from;
      return $this;
    }

    /**
     * 设置条件
     * @param string $where 设置查询条件
     * @return $this
     */
    public function where($where)
    {
      $this->where = "WHERE {$where}";
      return $this;
    }

    /**
     * 设置排序
     * @param string $orderby
     * @return $this
     */
    public function orderby($orderby)
    {
      $this->orderby = "ORDER BY {$orderby}";
      return $this;
    }

    /**
     * 限制记录数
     * @param int|string $limit
     * @return $this
     */
    public function limit($limit)
    {
      $this->limit = "LIMIT {$limit}";
      return $this;
    }

    /**
     * 设置要查询的字段
     * @param string $select
     * @return $this
     */
    public function select($select)
    {
      $this->select = $select;
      return $this;
    }

    /**
     * 查询记录
     * @param string $tableName 要查询的表名
     * @return $this
     */
    public function get($tableName = '')
    {
      $tableName = !empty($tableName) ? $tableName : $this->from;
      $this->sql = "SELECT {$this->select}  FROM {$tableName}  {$this->where} {$this->orderby} {$this->limit}";
      return $this;
    }

    public function result_array()
    {
      $res = mysql_query($this->sql);
      $data = array();
      if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_assoc($res)) {
          $data[] = $row;
        }
      }
      $this->init();
      return $data;
    }

    public function row_array()
    {
      $res = mysql_query($this->sql);
      $data = array();
      if (mysql_num_rows($res) > 0) {
        $data = mysql_fetch_assoc($res);
      }
      $this->init();
      return $data;
    }

    public function insert($tableName, $data)
    {
      $fields = implode(',', array_keys($data));
      $values = array();
      foreach (array_values($data) as $item) {
        $values[] = "'{$item}'";
      }
      $values = implode(',', $values);

      $this->sql = "INSERT INTO {$tableName}({$fields}) VALUES({$values})";

      mysql_query($this->sql);
      $this->init();
      if ($id = mysql_insert_id()) {
        return $id;
      } else {
        false;
      }
    }

    public function update($tableName, $data, $url)
    {
      $temp = array();
      foreach ($data as $k => $v) {
        $temp[] = "{$k}='{$v}'";
      }
      $temp = implode(',', $temp);

      $this->sql = "UPDATE {$tableName} SET {$temp} {$this->where}";
      mysql_query($this->sql);
      $this->init();
      if (mysql_affected_rows() > 0) {
        // return true;
        echo '<script type="text/javascript">alert("更新成功");location.href="'.$url.'"</script>';
      } else {
        return false;
      }
    }

    public function delete($tableName)
    {
      $this->sql = "DELETE FROM {$tableName} {$this->where}";
      mysql_query($this->sql);
      $this->init();
      if (mysql_affected_rows() > 0) {
        return true;
      } else {
        return false;
      }
    }

// Jeffery改动
    public function delMore($delname,$tableName,$url)
    {
     $delid=implode(",",$_POST[$delname]);
     $this->sql="DELETE FROM {$tableName} {$this->where} IN({$delid})";
     mysql_query($this->sql);
     if(mysql_affected_rows()>0)
     {
      success("删除成功",$url);
    }else{
      error("删除失败");
    }
  }

  public function count_all($tableName)
  {
    $this->sql = "SELECT COUNT(*) FROM {$tableName} {$this->where}";
    $res = mysql_query($this->sql);
    $data = array();
    if ($res != false && mysql_num_rows($res) == 1) {
      $data = mysql_fetch_assoc($res);
    }
    $this->init();
    return $data['COUNT(*)'];
  }
}
?>