##FormCreator##

**Open form**

	FormCreator::openForm();
	FormCreator::openForm($attributes);

**Close Form**

	FormCreator::closeForm();

**Input**

	FormCreator::text($attributes);
	FormCreator::hidden($attributes);
	FormCreator::email($attributes);
	FormCreator::password($attributes);

>$attributes : Mảng thuộc tính

	echo FormCreator::text(array('name' => 'username', 'id' => 'username'));
	echo FormCreator::hidden(array('name' => 'username', 'id' => 'username'));
	echo FormCreator::email(array('name' => 'username', 'id' => 'username'));
	echo FormCreator::password(array('name' => 'username', 'id' => 'username'));

**Radio**

	echo FormCreator::radio(array('name' => 'username', 'id' => 'username'));

**Checkbox**

	echo FormCreator::checkbox(array('name' => 'username', 'id' => 'username'));

**Select**

	echo FormCreator::select($data, $defaultValue, $attributes = array());

>$data : Dữ liệu đổ vào thẻ select `array($key => $value)`
>$defaultValue : Giá trị mặc định của select, là key của mảng data
>$attributes : Mảng thuộc tính của select, mặc định không có thuộc tính nào

Ví dụ:

	$data = array(1 => 'Cam', 3 => 'Quýt', 4 => 'Na');
	$defaultValue = 1;
	FormCreator::select($data, $defaultValue);

	// Hoặc
	$data = array(array(1 => 'Cam'), array( 3 => 'Quýt'), array(4 => 'Na'));
	$defaultValue = 1;
	FormCreator::select($data, $defaultValue);

**Textarea**

	echo FormCreator::textarea($content, $attributes = array());

>content : Nội dung của textarea
>$attributes : Mảng thuộc tính

**Button**

	echo FormCreator::button($type, $text, $attributes = array());

>$type : 'submit', 'reset';
>$text : Text button
>$attributes : Mảng thuộc tính

>Ví dụ:

	echo FormCreator::button('submit', 'Cập nhật');
	echo FormCreator::button('reset', 'Reset');

**Tạo control form-horizontal bootstrap**

	FormCreator::makeControl($title, $control);

>$title: Tiêu đề control
>$control: HTML control

Ví dụ:

	FormCreator::openForm();
	FormCreator::makeControl('UserName', FormCreator::text(array('name' => 'username')));
	FormCreator::makeControl('Password', FormCreator::password(array('name' => 'password')));
	FormCreator::makeButton();
	FormCreator::closeForm();