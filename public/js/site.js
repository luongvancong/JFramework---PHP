/**
 * Angular Register App
 */
var app = angular.module('app', []);

/**
 * Call box modal ( status, message )
 */
var BugModal = {
	init : function(options) {
		var default_options = {
			status : 'success',
			title : 'Thông báo',
			message : ''
		}

		var stt = ["success", "error", "warning"];

		var options = $.extend(default_options, options);

		if(stt.indexOf(options.status) <= 0) {
			options.status = "success";
		}

		this.getHtmlModal().success(function(html) {
			if($('#jt-modal').length <= 0) {
				$('body').append(html);
			}
			var $modal = $('#jt-modal');

			$modal.find('.icon-info')
					.removeClass('error')
					.removeClass('success')
					.removeClass('warning')
					.addClass(options.status);

			$modal.find('.modal-ctitle').html(options.title);

			$modal.find('.modal-message').html(options.message);

			$modal.modal();
		});

	},

	getHtmlModal : function() {
		return $.get('/templates_html/bug_modal.html');
	},

	close : function() {
		$('#jt-modal').modal('hide');
	}
}

$(function(){

	$('#banner-index').bxSlider({
		minSlides: 1,
	  	slideWidth: 940,
	  	slideMargin: 5,
	  	easing : 'linear',
	  	auto : true,
	  	mode : 'fade',
	  	controls : false,
	  	pager : false
	});

	/*Slide product profile*/
	var slider = $('#slider-products-1').bxSlider({
		minSlides: 1,
		maxSlides : 4,
		slideWidth: 243,
		infiniteLoop : false,
		slideMargin: 3,
		pager: false,
	  	controls : false,
	});

	// //Events for slider
	$('.left_promotion').click(function(){
		slider.goToPrevSlide();
		// return false;
	});
	$('.right_promotion').click(function(){
		slider.goToNextSlide();
		// return false;
	});

	$('#home-banner').find('.bx-wrapper .bx-viewport').css({
		'box-shadow': 'none',
		'border': '0'
	});


	var relateProductSlider = $('#slider-relate-products-cart').bxSlider({
		minSlides : 1,
		maxSlides: 1,
		slideWidth: 244,
		slideMargin :1,
		controls: false,
		pager: false,
	});

	$('#left-relate').click(function() {
		relateProductSlider.goToNextSlide();
	})

	$('#right-relate').click(function() {
		relateProductSlider.goToPrevSlide();
	})

	$('.js-remove-product-cart').on('click', function() {
		var $this = $(this);
		if(confirm("Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng")) {
			$.ajax({
				url : '/ajax/ajaxRemoveItemCart',
				type : 'POST',
				dataType : 'json',
				data : {
					product_id : $this.data('pid'),
					_token : $this.data('token')
				},
				success : function(data) {
					if(data) {
						window.location.reload();
					}else{
						alert('Không xóa được');
					}
				}
			})
		}
	});

	// $('.js-wkm-pitem-user-wall').wookmark({
	// 	 offset: 5,
	// 	 container : $('.js-workmark-user-wall')
	// });

	$('.js-workmark-user-wall').each(function() {
		var $this = $(this);
		$this.find('li').wookmark({
			offset : 5,
			container : $('#' + $this.attr('id'))
		});
	});

	$('.js-btn-follow').on('click', function() {
		var $this = $(this);
		$.ajax({
			url : '/ajax/set-follow-user',
			type : 'POST',
			dataType : 'json',
			data : {
				fid : $this.data('uid'),
				url_return : $this.data('urlreturn'),
				_token : $this.data('token')
			},
			success : function(data) {
				// Bỏ theo dõi thành công
				if(data.code === 1) {
					$this.text(data.text);
					$this.removeClass('btn-success').addClass('btn-danger');
				}
				// Theo dõi thành công
				else if(data.code === 4) {
					$this.text(data.text);
					$this.removeClass('btn-danger').addClass('btn-success');
				}
				// Đăng nhập
				else if(data.code === 2) {
					alert(data.message);
					window.location.href = data.url_login;
				}
				// Không thể tự follow mình
				else if(data.code === 3) {
					alert(data.message);
				}
				else{
					alert(data.message);
				}
			}
		});

		return false;
	});

	$('.search-bar input[type="text"]').focus(function() {
		$(this).css('width', '300px');
	});

	$(".select-cat").hover(function(){
		$(this).find(".type-search-hidden").removeClass("hidden");
	}, function() {
		$(this).find(".type-search-hidden").addClass("hidden");
	});


	$('.type-search-hidden .item').each(function() {
		var $this = $(this);
		if($this.data('selected') == 1) {
			$('.select-cat-inner .active').text($this.data('name'));
			$('#inp-searchtype-hidden').val($this.data('id'));
		}
	});

	$('.lazy').lazyload();


	/**
	 * Tạo bộ sưu tập trực tiếp từ trang cá nhân
	 */
	$('#form-create-collection').submit(function() {

      var $this = $(this);

      $.ajax({
      	url : '/ajax/create-collection',
      	type : 'POST',
      	dataType : 'json',
      	data : $this.serialize(),
      	success : function(data) {
      		if(data.code) {
      			$('#modal-create-collection').modal('hide');
      			BugModal.init({
      				status : 'success',
      				message : data.message
      			});
      		}else{
      			BugModal.init({
      				status: 'error',
      				message : data.message
      			});
      		}
      	}
      })

      return false;
   });

});