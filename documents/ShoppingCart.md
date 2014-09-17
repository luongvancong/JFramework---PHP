## Abstract Class Shopping Cart

**.Tạo class kế thừa ShoppingCart**

	class Cart extends ShoppingCart {
		public function getTotalMoney() {
			// Code here
		}
	}

**.Khởi tạo đối tượng ShoppingCart**

	$cart = new Cart($location);

>$location : ID khu vực

**. Thêm 1 item**

	$cart->add($productId, $childId = 0, $quantity, $options = array());

>$productId : ID sản phẩm

>$childId : ID sản phẩm con( Nếu có ), mặc định là 0

>$quantity : Số lượng thêm vào giỏ hàng

>$options : Mảng các options truyền thêm

**. Lấy thông tin 1 item trong giỏ hàng**

	$cart->getItem($productId, $childId = 0, $location = null);

>$productId : ID sản phẩm

>$childId : ID sản phẩm con( Nếu có ), mặc định là 0

>$location : ID khu vực, không truyền mặc định lấy location hiện tại

**. Lấy thông tin giỏ hàng theo khu vực**

	$cart->getContents($location);

**. Lấy tất cả thông tin giỏ hàng ( Không quan tâm tới khu vực )**

	$cart->getContents('all');

**. Xóa 1 item khỏi giỏ hàng của khu vực X**

	$cart->remove($productId, $childId = 0);

>$productId : ID sản phẩm

>$childId : ID sản phẩm con( Nếu có ), mặc định là 0

**. Xóa hết item của giỏ hàng thuộc khu vực X**

	$cart->clear($location);

**. Xóa tất cả giỏ hàng**

	$cart->clear('all');

**. Lấy cặp key productId và childId**

Key item trong giỏ hàng được tạo dựa trên productId và childId là duy nhất.

	$cart->getKeyArray($productId, $childId);

>$productId : ID sản phẩm

>$childId : ID sản phẩm con

**. Lấy productId trong key**

	$cart->getProductId($key);

>$key: Key được tạo ra từ productId và childID

**. Lấy childId trong key**

	$cart->getChildId($key);

>$key: Key được tạo ra từ productId và childID

