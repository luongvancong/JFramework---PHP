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

	abstract public function getTotalMoney();

	public function __construct($location) {
		if(!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) $_SESSION['cart'] = array();
		if(!isset($_SESSION['cart'][$location]) || !is_array($_SESSION['cart'][$location])) $_SESSION['cart'][$location] = array();

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
	public function add($productId, $childId = 0, $quantity, $options = array()) {
		$this->_itemsLocation[$this->getKeyArray($productId, $childId)] = array('quantity' => $quantity, 'options' => $options);
	}


	/**
	 * Remove an item
	 *
	 * @param  integer $id
	 */
	public function remove($productId, $childId = 0) {
		if(isset($this->_itemsLocation[$this->getKeyArray($productId, $childId)])) {
			unset($this->_itemsLocation[$this->getKeyArray($productId, $childId)]);
			return true;
		}

		return false;
	}


	/**
	 * Get an item in location
	 *
	 * @param  integer $id
	 * @param  integer $location
	 * @return mixed
	 */
	public function getItem($productId, $childId = 0, $location = null) {

		$id = $this->getKeyArray($productId, $childId);

		if( ($location !== null) && isset($this->_items[$location]) && isset($this->_items[$location][$id]) ) {
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


	/**
	 * Make key for item with productId and childId
	 *
	 * @param  mixed $productId
	 * @param  mixed $childId
	 * @return string
	 */
	public function getKeyArray($productId, $childId) {
		return $productId . '-' . $childId;
	}


	/**
	 * Get productId from key
	 *
	 * @param  mixed $key
	 * @return mixed
	 */
	public function getProductId($key) {
		$exp = explode('-', $key);
		return isset($exp[0]) ? $exp[0] : 0;
	}



	/**
	 * Get childId from key
	 *
	 * @param  mixed $key
	 * @return mixed
	 */
	public function getChildId($key) {
		$exp = explode('-', $key);
		return isset($exp[1]) ? $exp[1] : 0;
	}

}