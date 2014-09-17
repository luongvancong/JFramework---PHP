<?php
/**
 * Class Grid
 * @author Cong Luong <cong.itsoft@gmail.com>
 * @Last edit: 21/05/2014
 */
class DataGrid {

	public $_data = array();

	public $_callback = array();

	public $_fields = array();

	public $_fieldAttributes = array();

	public $_fieldSort = array();

	public $_fieldTitles = array();

	public $_formMaker;

	public $_pagination;

	public $_tableAttributse = array('class' => 'table table-bordered table-striped table-hover');

	public $_formSearchAttributes = array('method' => 'GET', 'action' => '', 'class' => 'grid-form-search');

	public $_arrayMatrixFormSearch = array();

	/**
	 * Constructor
	 * @param array $options
	 */
	public function __construct($options = array()) {
		$this->validateConfigInput($options);
		$this->_formMaker = new FormMaker();
		$this->_data = $options['data'];
		$this->_pagination = new Pagination(array(
         'limit_record' => $options['pagination']['limit_record'],
         'total_record' => $options['pagination']['total_record']
		));
	}


	/**
	 * Add Column
	 *
	 * @param string $field
	 * @param string $title
	 * @param integer $sort
	 * @param array   $attributes
	 * @param callback  $callback
	 */
	public function addColumn($field, $title = null, $sort = 0, $attributes = array(), $callback = null) {
		if(!$field) $field = time() . rand(111111, 999999);
		$this->_fields[]          = $field;
		$this->_fieldTitles[]     = $title;
		$this->_fieldAttributes[] = $attributes;
		$this->_fieldSort[]       = $sort;
		$this->_callback[]        = $callback;
	}


	/**
	 * Add search control
	 *
	 * @param integer $row
	 * @param integer $column
	 * @param string $title
	 * @param string $control
	 */
	public function addSearch($row, $column, $title, $control) {
		$this->_arrayMatrixFormSearch[$column][$row] = array('title' => $title, 'control' => $control);
	}


	/**
	 * Set attributes form search
	 *
	 * @param array $attributes
	 */
	public function setFormSearchAttributes($attributes) {
		$this->_formSearchAttributes = $attributes;
	}


	/**
	 * Form search
	 *
	 * @param  string $controls
	 * @return string
	 */
	public function getFormSearch() {
		$html = '';

		if(!empty($this->_arrayMatrixFormSearch)) {
			$html .= '<form '. $this->_formMaker->makeAttributes($this->_formSearchAttributes) .'>';
			$html .= '<table>';
			$html .= '<tr>';
			ksort($this->_arrayMatrixFormSearch);
			foreach($this->_arrayMatrixFormSearch as $column => $rows) {
				ksort($rows);
				$html .= '<td>';
				$html .= '<table>';
				foreach($rows as $info) {
					$html .= '<tr>
						<td align="right"><label>'. $info['title'] .'</label></td>
						<td width="200">'. $info['control'] .'</td>
					</tr>';
				}
				$html .= '</table>';
				$html .= '</td>';
			}
			$html .= '<td><input class="btn btn-primary" type="submit" value="Search" /></td>';
			$html .= '</tr>';
			$html .= '</table></form>';
		}

		return $html;
	}

	/**
	 * Render
	 *
	 * @param  boolean $echo
	 * @return mixed
	 */
	public function render($echo = true) {
		$html_search = '';
		$html_control = '';

		$html = '<div id="grid-container">'. $this->getFormSearch() .'
			<table '. $this->_formMaker->makeAttributes($this->_tableAttributse) .'>
				<thead>'. $this->getHeader() .'</thead>
				<tbody>'. $this->getBody() .'</tbody>
			</table> '. $this->_pagination->paginate_links() .'</div>
		';

		if($echo) echo $html; else return $html;
	}

	/**
	 * Get single row
	 *
	 * @param array $data
	 * @return string
	 */
	public function getSingleRow($data) {
		return '<tr>'. $this->getSingleColumn($data) .'</tr>';
	}


	/**
	 * Get single column
	 *
	 * @param string $data
	 * @return string
	 */
	public function getSingleColumn($data) {
		$html = '';

		foreach($this->_fields as $key => $field) {
			if(isset($data[$field]) && is_callable($this->_callback[$key])) {
				$html .= '<td>'. call_user_func($this->_callback[$key], $data, $data[$field]) .'</td>';
			}
			else if(isset($data[$field]) && !is_callable($this->_callback[$key])){
				$html .= '<td>'. $data[$field] .'</td>';
			}
			else if(!isset($data[$field]) && is_callable($this->_callback[$key])) {
				$html .= '<td>'. call_user_func($this->_callback[$key], $data, null) .'</td>';
			}
		}

		return $html;
	}


	/**
	 * Get table heading
	 *
	 * @return string
	 */
	public function getHeader() {

		$html = '';

		foreach($this->_fields as $key => $field) {
			if($this->_fieldSort[$key]) {
				$html .= '<th '. $this->_formMaker->makeAttributes($this->_fieldAttributes[$key]) .'><a href="'. $this->createLinkSort($field) .'">'. $this->_fieldTitles[$key] .'</a></th>';
			}else{
				$html .= '<th '. $this->_formMaker->makeAttributes($this->_fieldAttributes[$key]) .'>' . $this->_fieldTitles[$key] . '</th>';
			}
		}
		return $html;
	}


	/**
	 * Set table attributes
	 *
	 * @param array $attributes
	 */
	public function setTableAttributes($attributes) {
		$this->_tableAttributse = $attributes;
	}


	/**
	 * Get table body
	 *
	 * @return [type] [description]
	 */
	public function getBody() {
		$html = '';

		foreach($this->_data as $key => $value) {
			$html .= $this->getSingleRow($value);
		}

		return $html;
	}


	protected function createLinkSort($fieldSort , $typeSort = 'asc') {

		$queryString = array(
			'action'     => 'sort',
			'field_sort' => $fieldSort,
			'type_sort'  => $typeSort
		);

		$queryUrl = $_SERVER['QUERY_STRING'];

		$_get = array();

		foreach($_GET as $key => $value) {
			$_get[$key] = $value;
		}

		$_get['field_sort'] = $fieldSort;

		if(isset($_GET['type_sort']) && $_GET['type_sort'] == 'asc') {
			$_get['type_sort'] = 'desc';
		}else if(isset($_GET['type_sort']) && $_GET['type_sort'] == 'desc') {
			$_get['type_sort'] = 'asc';
		}else{
			$_get['type_sort'] = $typeSort;
		}

		$query = http_build_query($_get);

		if(strpos($queryUrl, '?')) {
			return '&' . $query;
		}

		return '?' . $query;
	}

	/**
	 * Make Delete Button
	 *
	 * @param  url $link
	 * @return html
	 */
	public function makeDeleteButton($link) {
		return '<a class="btn-action btn-delete-action" href="'. $link .'"><i class="fa fa-trash-o text-danger"></i></a>';
	}


	/**
	 * Make Edit button
	 *
	 * @param  url $link
	 * @return html
	 */
	public function makeEditButton($link) {
		return '<a class="btn-action btn-edit-action" href="'. $link .'"><i class="fa fa-edit"></i></a>';
	}


	/**
	 * Make active button
	 *
	 * @param  url $link
	 * @param  integer $currentActiveValue
	 * @return html
	 */
	public function makeActiveButton($link, $currentActiveValue) {
		$classActive = $currentActiveValue == 1 ? 'fa-check-square' : 'fa-square-o';
		return '<a class="btn-action btn-active-action" href="'. $link .'"><i class="fa '. $classActive .'"></i></a>';
	}

	protected function validateConfigInput($options) {
		if(!isset($options['data'])) throw new DataGridException(DataGridException::REQUIRED_DATA);
		if(!isset($options['pagination']['total_record'])) throw new DataGridException(DataGridException::REQUIRED_PAGINATION_TOTAL_RECORD);
		if(!isset($options['pagination']['limit_record'])) throw new DataGridException(DataGridException::REQUIRED_PAGINATION_LIMIT_RECORD);
	}
}


/**
 * Exception
 */
class DataGridException extends Exception {
	const REQUIRED_DATA = 3;
	const REQUIRED_PAGINATION_TOTAL_RECORD	= 5;
	const REQUIRED_PAGINATION_LIMIT_RECORD	= 6;

	public function __construct($code = null) {
		switch ($code) {

			case self::REQUIRED_DATA:
				$this->message = 'Options data mus be set';
				break;

			case self::REQUIRED_PAGINATION_LIMIT_RECORD:
				$this->message = 'Options ["pagination"]["limit_record"] must be set';
				break;

			case self::REQUIRED_PAGINATION_TOTAL_RECORD:
				$this->message = 'Options ["pagination"]["total_record"] must be set';
				break;

			default:
				$this->message = 'Something was wrong! Please try again!';
				break;
		}
	}
}