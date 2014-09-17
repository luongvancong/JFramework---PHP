##Form maker##

**Open form**

	echo FM::openForm();
	echo FM::openForm($attributes);

**Close Form**

	echo FM::closeForm();

**Input**

	echo FM::text($attributes);
	echo FM::hidden($attributes);
	echo FM::email($attributes);
	echo FM::password($attributes);

>$attributes : Mảng thuộc tính

	echo FM::text(array('name' => 'username', 'id' => 'username'));
	echo FM::hidden(array('name' => 'username', 'id' => 'username'));
	echo FM::email(array('name' => 'username', 'id' => 'username'));
	echo FM::password(array('name' => 'username', 'id' => 'username'));

**Radio**

	echo FM::radio(array('name' => 'username', 'id' => 'username'));

**Checkbox**

	echo FM::checkbox(array('name' => 'username', 'id' => 'username'));

**Select**

	echo FM::select($data, $defaultValue, $attributes = array());

>$data : Dữ liệu đổ vào thẻ select `array($key => $value)`

>$defaultValue : Giá trị mặc định của select, là key của mảng data

>$attributes : Mảng thuộc tính của select, mặc định không có thuộc tính nào

Ví dụ:

	$data = array(1 => 'Cam', 3 => 'Quýt', 4 => 'Na');
	$defaultValue = 1;
	echo FM::select($data, $defaultValue);

	// Hoặc
	$data = array(array(1 => 'Cam'), array( 3 => 'Quýt'), array(4 => 'Na'));
	$defaultValue = 1;
	echo FM::select($data, $defaultValue);

**Textarea**

	echo FM::textarea($content, $attributes = array());

>content : Nội dung của textarea
>$attributes : Mảng thuộc tính

**Button**

	echo FM::button($type, $text, $attributes = array());

>$type : 'submit', 'reset';
>$text : Text button
>$attributes : Mảng thuộc tính

>Ví dụ:

	echo FM::button('submit', 'Cập nhật');
	echo FM::button('reset', 'Reset');

**Tạo control form-horizontal bootstrap**

	FM::makeControl($title, $control);

>$title: Tiêu đề control
>$control: HTML control

Ví dụ:

	echo FM::openForm();
	echo FM::makeControl('UserName', FM::text(array('name' => 'username')));
	echo FM::makeControl('Password', FM::password(array('name' => 'password')));
	echo FM::makeButton();
	echo FM::closeForm();