# Mysql_class
数据库类

/*---------------------------------  使用数据库类的操作如下 -----------------------------*/	
	连接数据库

	// 数据库
	$db = new Mysql('b1508_dianzi');

	// 数据库、密码
	$db = new Mysql('b1508_dianzi', '123456');

	// 数据库、密码、账号
	$db = new Mysql('b1508_dianzi', '123456', 'root');

	// 数据库、密码、账号、主机名
	$db = new Mysql('b1508_dianzi', '123456', 'root', 'localhost');

	// 数据库、密码、账号、主机名、数据编码
	$db = new Mysql('b1508_dianzi', '123456', 'root', 'localhost', 'utf8');
	查询记录

	// 查询所有记录
	$db->get('tablename')->result_array();

	// 限定字段
	$db->select('id,name')->get('tablename')->result_array();

	// 限定条件
	$db->where('id=1')->get('tablename')->result_array();

	// 组合条件
	$db->where("name='allen' AND age=18")->get('tablename')->result_array();

	// 单排序
	$db->orderby('id desc')->get('tablename')->result_array();

	// 双排序
	$db->orderby('id desc,addtime desc')->get('tablename')->result_array();

	// 限定记录数
	$db->limit(10)->get('tablename')->result_array();

	// 限定记录偏移量和记录数
	$db->limit('0,10')->get('tablename')->result_array();
	生成查询结果

	// 生成单条记录
	$db->get('table_name')->row_array();

	// 生成多条记录
	$db->get('table_name')->result_array();
	插入数据

	$data = array(
	    'title'=>'I am title',
	    'content'=>'content...'
	);
	$db->insert('table_name', $data);
	更新数据

	$data = array(
	    'title'=>'I am title',
	    'content'=>'content...'
	);
	$db->where('id=1')->update('table_name', $data, "./demo.php");
	删除数据

	$db->where('id=1')->delete('table_name');

  $db->where("news_id")->delMore("del","ty_news","./news.php");


	$db->where('id IN (1,3,5)')->delete('table_name');
	统计记录总数

	// 无条件总计表记录总数
	$count = $db->count_all('table_name');

	// 有条件总计表记录总数
	$count = $db->where('cid=1')->count_all('table_name');
