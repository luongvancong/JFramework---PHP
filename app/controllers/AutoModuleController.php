<?php

class AutoModuleController extends Controller{

	public function __construct() {
		parent::__construct();
	}

	public function getIndex() {
		$action = Input::get('action');
		$table = Input::get('table');

		$rowFields = array();
		if($action == 'update') {
			$rowFields = $this->db->query("SHOW FIELDS FROM " . $table)->fetchAll();
		}

		$this->view->load('auto/index.php', compact('rowFields'));

		return false;
	}

	public function getCreate() {
		$this->view->load('auto/add.php');
	}

	public function postIndex() {
		$table = Input::get('table');
		$rowFields = $this->db->query("SHOW FIELDS FROM " . $table)->fetchAll();

		// Find primary key
		$primaryKey = '';
		foreach($rowFields as $row) {
			if(isset($row['Key']) && $row['Key'] == 'PRI') {
				$primaryKey = $row['Field'];
				break;
			}
		}

		// Make Controller
		$controllerTpl = file_get_contents(BASE_TEMPLATE . 'Controller.tpl');
		$controllerTpl = str_replace('className', ucfirst($table), $controllerTpl);
		file_put_contents(BASE_VIEW . 'auto/'. ucfirst($table) .'Controller.php', $controllerTpl);

		// Make Model
		$modelTpl = file_get_contents(BASE_TEMPLATE . 'Model.tpl');
		$modelTpl = str_replace(array('className', '@table@', '@primaryKey@'), array(ucfirst($table), strtolower($table), strtolower($primaryKey)), $modelTpl);
		file_put_contents(BASE_VIEW . 'auto/'. ucfirst($table) .'.php', $modelTpl);

		// Make add view
		$controls = "";
		$field_titles = Input::get('field_titles');
		$field_names = Input::get('field_names');
		$field_controls = Input::get('field_controls');

		foreach($field_titles as $key => $title) {
			switch ($field_controls[$key]) {
				case 1:
					$controlHtml = '$form->text(array("name" => "'. $field_names[$key] . '", "value" => Input::old(\''. $field_names[$key] .'\') , "class" => "form-control"))';
					break;

				case 2:
					$controlHtml = '$form->password(array("name" => "'. $field_names[$key] . '", "value" => Input::old(\''. $field_names[$key] .'\') , "class" => "form-control"))';
					break;

				case 3:
					$controlHtml = '$form->hidden(array("name" => "'. $field_names[$key] . '", "value" => Input::old(\''. $field_names[$key] .'\') ,"class" => "form-control"))';
					break;

				case 4:
					$controlHtml = '$form->select(array(), Input::old(\''. $field_names[$key] .'\') , array("name" => "'. $field_names[$key] . '", "class" => "form-control"))';
					break;

				case 5:
					$controlHtml = '$form->textarea(Input::old(\''. $field_names[$key] .'\'), array("name" => "'. $field_names[$key] . '", "class" => "form-control"))';
					break;

				case 6:
					$controlHtml = '$form->checkbox(array("name" => "'. $field_names[$key] . '", "value" => Input::old(\''. $field_names[$key] .'\') ,"class" => "form-control"))';
					break;

				case 7:
					$controlHtml = '$form->radio(array("name" => "'. $field_names[$key] . '", "value" => Input::old(\''. $field_names[$key] .'\') , "class" => "form-control"))';
					break;

				case 8:
					$controlHtml = '$form->file(array("name" => "'. $field_names[$key] . '", "class" => "form-control"))';
					break;

				case 9:
					$controlHtml = '$form->text(array("name" => "'. $field_names[$key] . '", "value" => Input::old(\''. $field_names[$key] .'\') ,"class" => "form-control date-picker"))';
					break;

				default:
					$controlHtml = '$form->text(array("name" => "'. $field_names[$key] . '", "value" => Input::old(\''. $field_names[$key] .'\') , "class" => "form-control"))';
					break;
			}
			$controls .= 'echo $form->makeControl("'. $title . '", '. $controlHtml .');' . "\n\t\t";
		}

		// echo $controls;die;
		$addViewTpl = file_get_contents(BASE_TEMPLATE . 'add/add.tpl');
		$addViewTpl = str_replace(array('@page_title@', '@form_controls@'), array(ucfirst($table), $controls), $addViewTpl);
		file_put_contents(BASE_VIEW . 'auto/add.php', $addViewTpl);

	}
}