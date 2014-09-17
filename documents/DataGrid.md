##DataGrid

**Chuẩn bị dữ liệu**

	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$limit = 5;
	$start = $limit * $page - $limit;

	$total = mysql_query('SELECT count(*) as count FROM posts WHERE 1');
	$total = mysql_fetch_assoc($total);
	$total = isset($total['count']) ? $total['count'] : 0;

	$orderby = !empty($_GET['field_sort']) ? $_GET['field_sort'] : 1;
	$order   = !empty($_GET['type_sort']) ? $_GET['type_sort'] : 'ASC';

	$sql_where = '';

	$pos_title = Input::get('pos_title');

	if($pos_title) $sql_where .= " AND pos_title LIKE '%". $pos_title ."%'";

	$res = mysql_query("SELECT * FROM posts WHERE 1 $sql_where ORDER BY $orderby $order LIMIT $start,  $limit");

	$data = array();
	while($row = mysql_fetch_assoc($res)) {
		$data[] = $row;
	}

**Khởi tạo đối tưọng DataGrid**

	$grid = new DataGrid(array(
	   'data' => $data,
	   'pagination' => array(
	      'current_page' => $page,
	      'total_record' => $total,
	      'limit_record' => $limit
	   )
	));

>$data: Mảng dữ liệu kết quả sau khi truy vấn từ mySql

>$page : Trang hiện tại

>$total : Tổng số bản ghi

>$limit: Số bản ghi muốn hiển thị trên 1 trang

**Thêm cột dữ liệu**

	$grid->addColumn($fieldName, $titleColumn, $sort, $attributes, $callback);

>$fieldName : Tên trưòng trong bảng dữ liệu

>$titleColumn : Tiêu đề cột

>$sort : Có sắp xếp hay không , 1 là sắp xếp, 0 là không sắp xếp

>$attributes : Mảng thuộc tính HTML của cột | array('id' => '', 'class' => '')

>$callback: Callback trả về dạng `function($item)`

>$item : Mảng dữ liệu tưong ứng với 1 row trong bảng dữ liệu, `print_r($item)` để biết thêm chi tiết

Ví dụ:

	$grid->addColumn('pos_picture', 'Avatar', 0, array(), function($item) {
		return '<img src="'. $picture .'" width="50" height="50" />';
	});

**Thêm control tìm kiếm**

	$grid->addSearch($row, $column, $title, $control);

>$row: Hàng nào, ví dụ: 1

>$column: Cột nào, ví dụ : 2

>$title: Tiêu đề control

>$control: HTML control

Ví dụ:

	$grid->addSearch(1,1, 'Từ khóa', '<input type="text" name="q">');

**Hiển thị dữ liệu dạng bảng**

	$grid->render($echo = true);

>$echo: true -> hiển thị ngay dữ liệu dạng bảng, false -> return ra HTML

Ví dụ:

	$grid->render()
	// Hoặc
	$html = $grid->render(false);
	echo $html;