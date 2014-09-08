var ProductDetail = {

	getInfoChildProduct : function() {
		var $ajax = $.ajax({
			url : '/ajax/getInfoChildProduct',
			dataType: 'json',
			type: 'GET',
			data : {
				color : $('#product_color').val(),
				size : $('#product_size').val(),
				product: $('#product_id').val()
			}
		});

		return $ajax;
	},

	changeInfoProduct: function(data) {
		if(data.code) {
			var _data = data.data;
			var picture = _data.picture;
			$('#product-dt-image').attr('src', picture);
			$('.dt-price-sale').html(_data.price_format);
			$('#product_child_id').val(_data.id);
			$('.zoomImg').attr('src', picture.replace('_lg', ''));
		}
	},

	onChangeColor: function() {
		ProductDetail.selected_color = 1;

		var $this = $(this);
		$('.dt-color-item').removeClass('fa-check-square');
		$this.toggleClass('fa-check-square');
		$('#product_color').val($this.data('colorid'));
		ProductDetail.getInfoChildProduct().success(ProductDetail.changeInfoProduct);
	},

	onChangeSize: function() {
		ProductDetail.selected_size = 1;

		var $this = $(this);
		$('.dt-size-item').removeAttr('style');
		$this.css('border', '1px solid red');
		$('#product_size').val($this.data('sizeid'));
		ProductDetail.getInfoChildProduct().success(ProductDetail.changeInfoProduct);
	},

	makeFacebookLike: function() {
		var $dt_facebook_button = $('#dt-facebook-button');
		$dt_facebook_button.html('<iframe src="//www.facebook.com/plugins/like.php?href='+ $dt_facebook_button.data('url') +'&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=true&amp;share=true&amp;height=21&amp;appId=779194325433159" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe>');
	},

	initZoomImage : function() {
		var $jquery_zoom = $('.jquery-zoom');
		$jquery_zoom.zoom({url: $jquery_zoom.data('image')});
	},

	isSelectedAttributes : function() {
		if($('#has_color').val() == 1 && !ProductDetail.selected_color) {
			alert('Vui lòng chọn màu sắc!');
			return false;
		}

		if($('#has_size').val() == 1 && !ProductDetail.selected_size) {
			alert('Vui lòng chọn size!');
			return false;
		}

		return true;
	},

	buyNow: function() {

		if(!ProductDetail.isSelectedAttributes()) {
			return false;
		}

		$.ajax({
			url : '/ajax/ajaxAddToCart',
			dataType : 'json',
			type : 'POST',
			data : {
				product_id : $('#product_id').val(),
				product_child_id : $('#product_child_id').val(),
				quantity : $('#js-input-qty').val(),
				_token : $('#_token').val()
			},
			success : function(data) {
				if(data.total > 0) {
					window.location.href = '/thanh-toan-don-hang';
				}
 			}
		});
	}
}

app.factory('ProductDetail', function() {
	return ProductDetail;
});

app.controller('productDetailCtrl', function($scope, $http, ProductDetail) {

	$scope.cart = {};

	/**
	 * Tính lại tiền, số lượng sp trong giỏ
	 */
	function recountCart(data) {
		$scope.cart.contents   = data.contents;
		$scope.cart.totalPrice = data.total_price;
		$scope.cart.totalItem  = data.total;
		$scope.cart.isEmpty    = data.total > 0 ? false : true;
	}

	$http.get('/ajax/getShoppingCartContents').success(function(data) {
		recountCart(data);
	});


	/**
	 * Thêm sản phẩm vào giỏ
	 */
	$scope.addCart = function() {

		if(!ProductDetail.isSelectedAttributes()) {
			return false;
		}

		$http.post('/ajax/ajaxAddToCart', {
			"_token" : document.getElementById("_token").value,
			"quantity" : document.getElementById('js-input-qty').value,
			"product_id" : document.getElementById('product_id').value,
			"product_child_id" : document.getElementById('product_child_id').value
		}).success(function(data) {
			if(data) {
				recountCart(data);
			}
		});
	}

	/**
	 * Thay đổi số lượng sản phẩm trong giỏ
	 */
	$scope.changeQuantity = function(id, quantity) {

		$http.post('/ajax/ajaxAddToCart', {
			"_token" : document.getElementById("_token").value,
			"quantity" : quantity,
			"product_id" : id,
			"product_child_id" : document.getElementById('product_child_id').value
		}).success(function(data) {
			if(data) {
				recountCart(data);
				$('#js-input-qty').val(quantity);
			}
		});
	}

	/**
	 * Xóa sản phẩm khỏi giỏ
	 */
	$scope.removeCartItem = function(id) {
		$http.post('/ajax/ajaxRemoveItemCart', {
			_token : document.getElementById("_token").value,
			"product_id" : id
		}).success(function(data) {
			if(data) {
				recountCart(data);
			}
		});
	}
});

app.directive('miniShoppingCart', function() {
	return {
		restrict : 'E',
		templateUrl : '/templates_angular/mini_cart.html'
	}
});


$(function() {

	ProductDetail.makeFacebookLike();
	ProductDetail.initZoomImage();

	$('.dt-color-item').click(ProductDetail.onChangeColor);
	$('.dt-size-item').click(ProductDetail.onChangeSize);

	$('#product-dt-other-images-slider').bxSlider({
	  	minSlides: 8,
	  	maxSlides: 8,
	  	slideWidth: 400,
	  	slideMargin: 1,
	  	controls: false,
	  	pager: false
	});

	$(document).on('mouseover', '#product-dt-other-images-slider .image-item' ,function() {

		var $this = $(this);
		var url_image = $this.data('image');
		$('#product-dt-image').attr('src', url_image);

		// Reconfig zoom
		$('.zoomImg').attr('src', url_image.replace('lg_', ''));
	});


	var $js_input_qty = $('.js-input-qty', '#product-dt-top-right');

	var relateProductSlider = $('#slider-relate-products').bxSlider({
		minSlides : 1,
		maxSlides: 1,
		slideWidth: 244,
		slideMargin :1,
		controls: false,
		pager: false,
	})

	$('.relate-products .controls .right').click(function() {
		relateProductSlider.goToNextSlide();
	})

	$('.relate-products .controls .left').click(function() {
		relateProductSlider.goToPrevSlide();
	})

});