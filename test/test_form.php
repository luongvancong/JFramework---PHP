<?php

require_once('../simpletest/autorun.php');
require_once '../libs/Form.php';
require_once '../libs/FormFacade.php';

class TestForm extends UnitTestCase {
	public function testOpenForm() {
		$this->assertTrue(FM::openForm());
	}

	public function testCloseForm() {
		$this->assertTrue(FM::closeForm());
	}

	public function testMergeAttr() {
		$this->assertEqual(array('class' => 'a', 'id' => 'b'), FM::mergeAttributes(array('class' => 'a'), array('id' => 'b')));
	}

	public function testInput() {
		$this->assertTrue(FM::text(['name' => 'name']));
	}
}