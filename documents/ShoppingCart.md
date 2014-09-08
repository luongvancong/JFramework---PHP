## Shopping Cart

**1.Khởi tạo đối tượng ShoppingCart**

	$cart = new ShoppingCart($location);

>$location : ID khu vực

**2. Thêm 1 item**

	$cart->add($productId, $quantity, $options = array());

>$productId : ID sản phẩm
>$quantity : Số lượng thêm vào giỏ hàng
>$options : Mảng các options truyền thêm

**3. Lấy thông tin 1 item trong giỏ hàng**

	$cart->getItem($id, $location = null);

>$id : ID sản phẩm
>$location : ID khu vực, không truyền mặc định lấy location hiện tại

**4. Lấy thông tin giỏ hàng theo khu vực**

	$cart->getContents($location);

**5. Lấy tất cả thông tin giỏ hàng ( Không quan tâm tới khu vực )**

	$cart->getContents('all');

**6. Xóa 1 item khỏi giỏ hàng của khu vực X**

	$cart->remove($productId);

**7. Xóa hết item của giỏ hàng thuộc khu vực X**

	$cart->clear($location);

**8. Xóa tất cả giỏ hàng**

	$cart->clear('all');