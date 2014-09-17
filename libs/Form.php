<?php
/**
 * Class FormMaker
 * Support Bootstrap 3.0 , Font Awesome
 * @Author : Cong Luong
 * @Version: 1.0
 * @Lat edit : 06/09/2014
 */
class FormMaker {

	private $name;

	public $isLaravel = true;

	private $default_attributes_control = array('class' => 'form-control');

	private $default_attributes_button = array('class' => 'btn btn-default');

	private $default_attributes_form = array('class'			=> 'form form-horizontal',
															'enctype'      => 'multipart/form-data',
															'action'			=> '',
															'method'			=> 'POST',
															'autocomplete'	=> 'off');

	private $default_attributes_table = array('class' => 'table');

	protected $input_methods = array('text', 'hidden', 'radio', 'checkbox', 'email', 'file', 'submit', 'reset', 'password');

	protected $template = '<div class="form-group">
									<label class="col-sm-2 control-label">:title_control</label>
									<div class="col-sm-6">:control :error_message</div>
								</div>';

	public function __construct($options = array()) {

	}

	/**
	 * Open Form
	 * @param  array 	$attr  Attributes
	 * @param  bool 	$echo  Echo
	 * @return mixed
	 */
	public function openForm($attr = array()) {
		$attr = array_merge($this->default_attributes_form, $attr);
		$html =  '<form '. $this->makeAttributes($attr) .'>';
		return $html;
	}


	/**
	 * Close Form
	 * @param  boolean $echo
	 * @return mixed
	 */
	public function closeForm() {
		$html = '</form>';
		return $html;
	}


	/**
	 * Open table
	 *
	 * @param  array  $attr Array attributes
	 * @return string
	 */
	public function openTable($attr = array()) {
		return '<table '. $this->makeAttributes(array_merge($this->default_attributes_table, $attr)) .'>';
	}


	/**
	 * Close table
	 *
	 * @return string
	 */
	public function closeTable() {
		return '</table>';
	}


	/**
	 * Input
	 * @param  string $type    [description]
	 * @param  [type] $title   [description]
	 * @param  array  $attr    [description]
	 * @return [type]          [description]
	 */
	public function input($type = 'text', $attr = array()) {

		$attr = $this->mergeAttributes($this->default_attributes_control, $attr, array('type' => $type));

		$this->validateAttribute($attr);

		$this->name = $attr['name'];

		if( isset($attr['type']) && ( $attr['type'] == 'submit'
		   || $attr['type'] == 'reset'
		   || $attr['type'] == 'file'
		   || $attr['type'] == 'radio'
		   || $attr['type'] == 'checkbox'
		   ) ) {
			$attr['class'] = str_replace('form-control', '', $attr['class']);
		}

		$html = '<input '. $this->makeAttributes($attr) .' />';

		return $html;
	}

	/**
	 * Textarea
	 * @param  [type] $title [description]
	 * @param  string $text  [description]
	 * @param  array  $attr  [description]
	 * @return mixed
	 */
	public function textarea($text, $attr = array()) {
		$attr	= $this->mergeAttributes($this->default_attributes_control, $attr);

		$this->validateAttribute($attr);

		$this->name = $attr['name'];

		$html	= '<textarea '. $this->makeAttributes($attr) .'>'. $text .'</textarea>';
		return $html;
	}


	/**
	 * Button
	 * @param  string $type    Type button
	 * @param  string $text    Text button
	 * @param  array  $attr    Attributes
	 * @return mixed
	 */
	public function button($type = 'submit', $text, $attr = array()) {

		if($type == 'submit') {
			$class = 'btn btn-sm btn-primary';
		}
		else if($type == 'reset'){
			$class = 'btn btn-sm btn-danger';
		}
		else {
			$class = 'btn btn-sm btn-default';
		}

		$attr_default = array('class' => $class);

		$attr	= $this->mergeAttributes($this->default_attributes_button, $attr_default, $attr, array('type' => $type));

		$html = '<button '. $this->makeAttributes($attr) .'>'. $text .'</button>';

		return $html;
	}

	/**
	 * Make Group Button
	 *
	 * @return html
	 */
	public function makeButton() {
		$template = $this->getTemplateHtml();

		$button = '<a class="btn btn-link" href="javascript:window.history.go(-1)">Hủy</a>
		         <button type="reset" class="btn">Xóa dữ liệu</button>
		         <button type="submit" class="btn btn-success">Cập nhật</button>';

		return str_replace(array(':title_control', ':control', ':error_message'), array('', $button, ''), $template);
	}


	/**
	 * Image
	 * @param  [type]  $title  [description]
	 * @param  array   $attr   [description]
	 * @return [type]          [description]
	 */
	public function image($attr = array()) {
		$html = '<img '. $this->makeAttributes($attr) .'/>';
		return $html;
	}


	/**
	 * Paragraph static
	 * @param  string $text
	 * @param  array  $att
	 * @return string
	 */
	public function paragraph($text, $att = array()) {
		$html = '<p class="form-control-static">'. $text .'</p>';
		return $html;
	}


	/**
	 * Select
	 * @param  string  $title   Title
	 * @param  array  $data    Data
	 * @param  mixed  $default Default value
	 * @param  array   $attr    Attributes
	 * @return mixed
	 */
	public function select($data, $default, $attr = array()) {
		$attr	= $this->mergeAttributes($this->default_attributes_control, $attr);

		$this->validateAttribute($attr);

		$this->name = $attr['name'];

		$html	= '<select '. $this->makeAttributes($attr) .'>';
		if(is_array($data) && !empty($data)) {
			foreach($data as $k => $v) {
				if(is_array($v)) {
					$html .= '<optgroup label="'. $k .'">';
					foreach($v as $k1 => $v1) {
						$selected = $default == $k ? 'selected="selected"' : '';
						$html .= '<option value="'. $k1 .'" '. $selected .'>'. $v1 .'</option>';
					}
					$html .= '</optgroup>';
				}
				else {
					$selected = $default == $k ? 'selected="selected"' : '';
					$html .= '<option value="'. $k .'" '. $selected .'>'. $v .'</option>';
				}
			}
		}
		$html .='</select>';

		return $html;
	}


	/**
	 * Generate string attributes
	 * @param  array $attributes
	 * @return string
	 */
	public function makeAttributes($attributes) {

		$stringAttribute = '';

		if(is_array($attributes)) {
			foreach($attributes as $key => $value) {
				$stringAttribute .= "$key=\"$value\" ";
			}
		}else{
			$stringAttribute = @strval($attributes);
		}

		return trim($stringAttribute, ' ');
	}


   /**
	* Merge Attributes
	*
	* @access public
	*
	* @return array attributes.
   */
	public function mergeAttributes() {

		$args = func_get_args();

		$temp_attributes = array();

		$attributes = array();

		foreach($args as $key => $array_attr) {
			foreach($array_attr as $name_attr => $value_attr) {
				if($name_attr == 'class') {
					$temp_attributes[$name_attr][] = $value_attr;
				}else{
					$temp_attributes[$name_attr] = $value_attr;
				}
			}
		}

		foreach($temp_attributes as $name_attr => $value_attr) {
			if($name_attr == 'class') {
				$attributes[$name_attr] = implode(' ', $value_attr);
			}else{
				$attributes[$name_attr] = $value_attr;
			}
		}

		return $attributes;
	}

	/**
	 * Set template html for each control
	 *
	 * @param html $template
	 */
	public function setTemplateHtml($template) {
		$this->template = $template;
	}


	/**
	 * Get template html for each control
	 *
	 * @return html
	 */
	public function getTemplateHtml() {
		return $this->template;
	}


	/**
	 * Return control html item
	 *
	 * @param  string $title        Title control
	 * @param  string $control      Html control
	 * @param  string $errorMessage Error message
	 * @return html
	 */
	public function makeControl($title, $control, $required = false, $errorMessage = '') {

		if( ($required === true || $required === 1) && $title != '') $title .= ' <b class="text-danger">*</b>';

		$template = $this->getTemplateHtml();

		if($this->isLaravel && Session::has('errors')) {
			$errors = Session::get('errors');
			if(is_object($errors) && $errors->has($this->name) && ( strpos($control, 'button') === false || strpos($control, 'submit') === false) ) {
				$has_error = 'has-error';
				$message = $errors->first($this->name, '<span class="help-inline text-danger">:message</span>');
				$html = str_replace(array(':title_control', ':control', ':error_message'), array($title, $control, $message), $template);
				return $html;
			}
		}

		return str_replace(array(':title_control', ':control', ':error_message'), array($title, $control, $errorMessage), $template);

	}


	/**
	 * Get method avaiable
	 *
	 * @return array
	 */
	public function getMethods() {
		return $this->input_methods;
	}

	private function validateAttribute($attr) {
		if(!isset($attr['name'])) {
			throw new FormException(FormException::NAME_ATTRIBUTE_REQUIRED);
		}
	}


	/**
	 * Handle magic __call
	 */
	public function __call($method, $args) {
		if(in_array($method, $this->input_methods)) {
			array_unshift($args, $method);
			return call_user_func_array(array($this, 'input'), $args);
		}
		// Sửa lại hàm setTemplate và setTemplateTable về chung 1 hàm makeControl
		elseif($method == 'setTemplate' || $method == 'setTemplateTable') {
			return call_user_func_array(array($this, 'makeControl'), $args);
		}
		else{
			throw new FormException(FormException::METHOD_NOT_EXIST, $method);
		}
	}

}

class FormException extends Exception {

	const METHOD_NOT_EXIST = 0;

	const NAME_ATTRIBUTE_REQUIRED = 1;

	public function __construct($code = null, $method = null) {

		switch ($code) {
			case FormException::METHOD_NOT_EXIST:
				$this->message = 'Form::' . $method . ' is not exits';
				break;

			case FormException::NAME_ATTRIBUTE_REQUIRED:
				$this->message = 'Name attribute must be set';
				break;

			default:
				$this->message = 'Something was wrong! Please try again!';
				break;
		}
	}
}