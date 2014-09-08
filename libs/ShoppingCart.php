<?php
/**
 * Abstract Class ShoppingCart
 *
 * @author Cong Luong <cong.itsoft@gmail.com>
 * @version 1.0
 * @create 04/09/2014
 * @last edit 08/09/2014
 */
abstract class ShoppingCart {

	/**
	 * Location
	 *
	 * @var integer
	 */
	protected $_location = 0;

	/**
	 * Items
	 *
	 * @var array
	 */
	protected $_items = array();


	/**
	 * Items in location
	 *
	 * @var array
	 */
	protected $_itemsLocation = array();

	public function __construct($location) {
		if(!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) $_SESSION['cart'] = array();
		if(!isset($_SESSION['cart'][$location]) || !is_array($_SESSION['cart'][$location])) $_SESSION['cart'][$location] = array();

		$this->_location = $location;
		$this->_itemsLocation = & $_SESSION['cart'][$location];
		$this->_items = & $_SESSION['cart'];
	}


	/**
	 * Add an item to cart
	 *
	 * @param integer $id
	 * @param integer $quantity
	 * @param array  $options
	 */
	public function add($id, $quantity, $options = array()) {
		$this->_itemsLocation[$id] = array('quantity' => $quantity, 'options' => $options);
	}


	/**
	 * Remove an item
	 *
	 * @param  integer $id
	 */
	public function remove($id) {
		unset($this->_itemsLocation[$id]);
	}


	/**
	 * Get an item in location
	 *
	 * @param  integer $id
	 * @param  integer $location
	 * @return mixed
	 */
	public function getItem($id, $location = null) {
		if( ($location !== null) && isset($this->_items[$location]) && isset($this->_items[$location][$id])) {
			return $this->_items[$location][$id];
		}

		return isset($this->_itemsLocation[$id]) ? $this->_itemsLocation[$id] : null;
	}


	/**
	 * Get contents cart
	 *
	 * @param  mixed $location | if $location == 'all' then return all items
	 * @return array
	 */
	public function getContents($location = null) {
		if( ($location !== null && isset($_SESSION['cart'][$location])) || ($location == null) ) {
			return $this->_itemsLocation;
		}
		else if($location == 'all') {
			return $this->_items;
		}

		return array();
	}


	/**
	 * Count Items
	 *
	 * @return integer
	 */
	public function count() {
		return count($this->_itemsLocation);
	}


	/**
	 * Clear cart
	 *
	 * @param  mixed $location | if $location == 'all' then clear all
	 * @return void
	 */
	public function clear($location = null) {
		if( ($location !== null && isset($_SESSION['cart'][$location])) || ($location == null) ) {
			$this->_itemsLocation = array();
		}
		else if($location == 'all'){
			$this->_items = array();
		}
	}
}