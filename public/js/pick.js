Element.prototype.remove = function() {
   this.parentElement.removeChild(this);
}

NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
   for(var i = 0, len = this.length; i < len; i++) {
      if(this[i] && this[i].parentElement) {
         this[i].parentElement.removeChild(this[i]);
      }
   }
}

var workspace = {};
workspace.tools = {};
workspace.tools.Base64 = {};
/**
 * Base64 toolkit
 * Đối tượng này dùng để hash 1 chuỗi thành dạng base64
 */
workspace.tools.Base64 = {
	// private property
	_keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

	// public method for encoding
	encode: function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;

		input = workspace.tools.Base64._utf8_encode(input);

		while (i < input.length) {

			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);

			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;

			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}

			output = output +
				this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
				this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

		}

		return output;
	},

	// public method for decoding
	decode: function (input) {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;

		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

		while (i < input.length) {

			enc1 = this._keyStr.indexOf(input.charAt(i++));
			enc2 = this._keyStr.indexOf(input.charAt(i++));
			enc3 = this._keyStr.indexOf(input.charAt(i++));
			enc4 = this._keyStr.indexOf(input.charAt(i++));

			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;

			output = output + String.fromCharCode(chr1);

			if (enc3 != 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 != 64) {
				output = output + String.fromCharCode(chr3);
			}

		}

		output = workspace.tools.Base64._utf8_decode(output);

		return output;

	},

	// private method for UTF-8 encoding
	_utf8_encode: function (string) {
		string = string.replace(/\r\n/g, "\n");
		var utftext = "";

		for (var n = 0; n < string.length; n++) {

			var c = string.charCodeAt(n);

			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if ((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}

		}

		return utftext;
	},

	// private method for UTF-8 decoding
	_utf8_decode: function (utftext) {
		var string = "";
		var i = 0;
		var c = 0, c1 = 0, c2 = 0;

		while (i < utftext.length) {

			c = utftext.charCodeAt(i);

			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if ((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i + 1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i + 1);
				c3 = utftext.charCodeAt(i + 2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}

		}

		return string;
	}
};


// Check domain
var hostname = location.hostname

// Những domain có sản phẩm cần lấy
var domain_allows = ['localhost', 'sea.taobao.com', 'item.taobao.com' , 'tmall.com', 'detail.tmall.com', 'item.jd.com'];

// Kiểu sp hay sưu tập ảnh bình thường
var type_pick = 1; // 1: Lấy sản phẩm

if(domain_allows.indexOf(hostname) < 0) { // Sưu tập ảnh
	type_pick = 0;
}


// URL execute
var DEVELOPMENT = 1;

var url_get_images  = 'http://waa.vn/account/products/add';
var url_loading_gif = 'http://waa.vn/assets/img/loading.gif';

// Nếu là sưu tập ảnh
if(type_pick == 0) {
	url_get_images = 'http://waa.vn/account/picture/add';
}

var body = document.getElementsByTagName('body')[0];
body.style.overflow = "hidden";

// Custome style nav-bar tmall
var tmall_site_nav = document.getElementById('site-nav');
var detail_taobao = document.getElementById('detail');
if(tmall_site_nav && hostname == 'detail.tmall.com'){
	tmall_site_nav.style.zIndex = 99;
}
if(detail_taobao && hostname == 'item.taobao.com') {
	detail_taobao.style.zIndex  = 99;
	document.getElementById('J_SiteNav').style.zIndex = 99;
}


function openWindow(url){
  window.open(url, '_blank');
  window.focus();
}

workspace.pickImages = {

	findImagesInPage : function() {
		var images = document.images;
		return images;
	},

	closeBackdrop : function() {
		body.style.overflow = 'initial';
		var container = document.getElementById('clv-container-bg');

		if(container) {
			container.remove();
		}

		this.closeModal();
	},

	closeModal : function() {
		var modal = document.getElementById('c-modal-bound');
		if(modal) modal.style.display = 'none';
	},

	onChooseImage : function(obj) {
		var price = 0;
		var description = '';
		var shop_link = '';
		// Get Price
		if(hostname == 'detail.tmall.com') {

			try {
				price = document.querySelector('#J_PromoPrice .tm-price').innerHTML;
			} catch(err) {
				console.log('#J_PromoPrice .tm-price not found');
			}
			// return false;
		}
		else if(hostname == 'item.taobao.com') {

			var tb_rmb = document.querySelector('#J_PromoPrice .tb-rmb');
			if(tb_rmb) tb_rmb.remove();
			var price_node = document.querySelector('#J_PromoPrice .tb-rmb-num');
			if(price_node && price_node.innerHTML) {
				price = price_node.innerHTML;
			}
			else if(document.querySelector('#J_PromoPrice .tm-price')) {
				price = document.querySelector('#J_PromoPrice .tm-price').innerHTML;
			}else if(document.querySelector('#J_StrPrice .tb-rmb-num')) {
				price = document.querySelector('#J_StrPrice .tb-rmb-num').innerHTML;
			}

			var element_shop_link = document.querySelector('.tb-shop-name a');
			if(element_shop_link) {
				shop_link = element_shop_link.getAttribute('href');
			}

			if(shop_link.indexOf('?') >= 0) {
				shop_link = shop_link.substr(0, shop_link.indexOf('?'));
			}

			shop_link = shop_link.replace('http://', '');
			shop_link = shop_link.replace('https://', '');

		}else if(hostname == 'item.jd.com') {
			try{
				price = document.getElementById('jd-price').innerHTML;
				price = price.replace('￥', '');
			}catch (err) {
				console.log('#jd-price not found');
			}
		}


		// Get image description
		var array_images_description = getImagesDescription();

		// Request uri
		var data = {
			'picture' : obj.getAttribute('data-picture'),
			'price' : price,
			'description' : JSON.stringify(array_images_description),
			'source' : window.location.href,
			'type_pick' : type_pick,
			'shop_link' : shop_link
		};

		var request_uri = '?data=' + workspace.tools.Base64.encode(JSON.stringify(data)) + '&add_type=crawler';
		openWindow(url_get_images + request_uri);
	},

	modal : function(message) {

	},

	getHtmlModalLoading : function() {
		var modal_container_container = document.createElement('div');
		var modal_container = document.createElement('div');
		var modal_header = document.createElement('div');
		var modal_body  = document.createElement('div');
		var modal_wrapper = document.createElement('div');
		var modal_close_button = document.createElement('div');
		var modal_footer = document.createElement('div');

		modal_container.setAttribute('id', 'c-modal-container');
		modal_container.style.width = '400px';
		modal_container.style.background = '#fff';
		modal_container.style.position = 'fixed';
		modal_container.style.left = '35%';
		modal_container.style.top = '19%';
		modal_container.style.zIndex = '10000000000';
		modal_container.style.boxShadow = '0 0 5px rgba(0,0,0,4)';

		modal_body.style.padding = '10px';

		modal_header.setAttribute('id', 'c-modal-header');
		modal_header.style.borderBottom = '1px solid #ccc';
		modal_header.style.padding = '8px';
		modal_header.style.fontWeight = 'bold';
		modal_header.innerHTML = 'Thông báo';

		modal_wrapper.setAttribute('id', 'c-modal-wrapper');

		modal_body.setAttribute('id', 'c-modal-body');
		modal_body.innerHTML = 'Bạn vui lòng đợi cho đến khi trang web được tải xong. <img style="position: relative;top: 4px;" src="'+ url_loading_gif +'" />';

		modal_footer.style.overflow = 'hidden';

		modal_close_button.style.border     = '1px solid #ccc';
		modal_close_button.style.textAlign  = 'center';
		modal_close_button.style.background = 'rgb(235, 28, 28)';
		modal_close_button.style.color      = '#fff';
		modal_close_button.style.cssFloat      = 'right';
		modal_close_button.style.padding    = '5px 15px';
		modal_close_button.style.margin     = '0px 5px 5px 0';
		modal_close_button.style.border     = 'none';
		modal_close_button.style.cursor     = 'pointer';
		modal_close_button.setAttribute('onclick', 'javascript:document.getElementById("c-modal-container").remove()');
		modal_close_button.innerHTML        = 'Đóng';

		modal_wrapper.appendChild(modal_header);
		modal_wrapper.appendChild(modal_body);
		modal_wrapper.appendChild(modal_close_button);
		modal_container.appendChild(modal_wrapper);

		modal_container_container.appendChild(modal_container);

		return modal_container_container.innerHTML;
	},

	getHtmlBackdrop : function() {
		var temp_div_wrapper = document.createElement('div');
		var div_wrapper = document.createElement('div');
		div_wrapper.setAttribute('id', 'clv-backdrop');
		div_wrapper.style.height     = '100%';
		div_wrapper.style.width      = '100%';
		div_wrapper.style.position   = "fixed";
		div_wrapper.style.top        = "0";
		div_wrapper.style.background = "rgba(0,0,0, .7)";
		div_wrapper.style.zIndex 		= "999999999";
		div_wrapper.style.overflow    = 'auto';
		temp_div_wrapper.appendChild(div_wrapper);
		return temp_div_wrapper.innerHTML;
	},

	showBackdrop : function() {
		//Div bound container
		var div_container = document.getElementById('clv-backdrop-container');
		if(!div_container) {
			div_container = document.createElement('div');
			div_container.setAttribute('id', 'clv-backdrop-container');
			body.appendChild(div_container);
		}

		this._backdrop_element = div_container;

		div_container.innerHTML = this.getHtmlBackdrop();
	},

	hideBackrop : function() {
		if(this._backdrop_element) this._backdrop_element.remove();
	},

	init : function() {

		try {
			document.getElementById('clv-container-bg').remove();
		} catch(err) {
			console.log(err.message);
		}

		//Div bound container
		var div_container = document.createElement('div');
		div_container.setAttribute('id', 'clv-container-bg');
		body.appendChild(div_container);

		// Div container
		var div_wrapper = document.createElement('div');
		div_wrapper.setAttribute('id', 'clv-wrapper');
		div_wrapper.style.height     = '100%';
		div_wrapper.style.width      = '100%';
		div_wrapper.style.position   = "fixed";
		div_wrapper.style.top        = "0";
		div_wrapper.style.background = "rgba(0,0,0, .7)";
		div_wrapper.style.zIndex 		= "999999999";
		div_wrapper.style.overflow    = 'auto';
		div_container.appendChild(div_wrapper);

		// Close button
		var button_close = document.createElement('div');
		button_close.setAttribute('class', 'clv-btn-close');
		button_close.setAttribute('onclick', 'workspace.pickImages.closeBackdrop()');
		button_close.innerHTML = 'Thoát';
		button_close.style.padding = "5px 10px";
		button_close.style.background = "#fff";
		button_close.style.color = "red";
		button_close.style.position = "fixed";
		button_close.style.right = "25px";
		button_close.style.top = "30px";
		button_close.style.cursor = "pointer";
		div_wrapper.appendChild(button_close);

		// Bound list images
		var div_list_images = document.createElement('div');
		div_list_images.setAttribute('class', 'clv-list-images');
		div_list_images.style.width = "1170px";
		div_list_images.style.margin = "0px auto";
		div_list_images.style.overflow = "hidden";
		div_wrapper.appendChild(div_list_images);

		var images = this.findImagesInPage();

		try {
			for(var i in images) {
				var imgObj = new Image();

				imgObj.src = images[i].src;
				if(imgObj.width >= 300) {
					// console.log(imgObj.width + 'x' + imgObj.height);
					// Image
					var div_img = document.createElement('div');
					div_img.setAttribute('class', 'clv-div-crop-img');
					div_img.style.cssFloat    = 'left';
					div_img.style.width    = "220px";
					div_img.style.height   = "200px";
					div_img.style.margin   = "5px";
					div_img.style.overflow = "hidden";
					div_img.style.position = 'relative';
					div_img.style.background = '#fff';

					var img_child = document.createElement('img');
					img_child.setAttribute('src', images[i].getAttribute('src'));
					div_img.appendChild(img_child);

					// If small height then make it center position
					if(imgObj.height < 200) {
						images[i].style.position = 'absolute';
						images[i].style.top      = '25%';
					}
					images[i].style.width    = '100%';

					// Button pick
					var button_pick = document.createElement('div');
					button_pick.setAttribute('class', 'clv-pick-picture');
					button_pick.setAttribute('onclick', 'workspace.pickImages.onChooseImage(this)');
					button_pick.setAttribute('data-picture', images[i].getAttribute('src'));
					button_pick.innerHTML          = "Chọn";
					button_pick.style.position     = 'absolute';
					button_pick.style.top          = "40%";
					button_pick.style.left         = "42%";
					button_pick.style.background   = "#fff";
					button_pick.style.color        = "red";
					button_pick.style.fontWeight   = "bold";
					button_pick.style.borderRadius = "3px";
					button_pick.style.padding      = "5px 10px";
					button_pick.style.cursor       = "pointer";
					button_pick.style.border = "1px solid #ccc";
					div_img.appendChild(button_pick);

					document.getElementsByClassName('clv-list-images')[0].appendChild(div_img);
				}

			}
		} catch(err) {
			console.log(err.message);
		}
	}
}


function callAjax(options) {
	var method = 'POST';
	var params = options.params;
	var url = options.url;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open(method, url , true);
	// xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send(params);
}


function is_object(a) {
	return typeof a == 'object' ? true : false;
}

function getImagesDescription() {
	var element_description      = null;
	var array_images_description = [];
	var temp_images_element      = [];

	if(hostname == 'item.taobao.com') {
		try {
			element_description = document.getElementById('description')
			temp_images_element = element_description.querySelectorAll('p img');
		} catch(err) {
			console.log(err.message);
		}
	}
	else if(hostname == 'detail.tmall.com'){
		try {
			element_description = document.getElementById('description')
			temp_images_element = element_description.querySelectorAll('img');
		} catch(err) {
			console.log(err.message);
		}
	}else if(hostname == 'item.jd.com') {
		try {
			element_description = document.getElementById('product-detail');
			temp_images_element = element_description.querySelectorAll('img');
		} catch(err) {
			console.log(err.message);
		}
	}

	for(var j in temp_images_element) {
		if(j == 40) break;

		try {
			// If isset data-ks-lazyload then take it, not take src
			if(temp_images_element[j].hasAttribute('data-ks-lazyload')) {
				temp_images_element[j].setAttribute('src', temp_images_element[j].getAttribute('data-ks-lazyload'));
			}

			var image_path = temp_images_element[j].src;
			array_images_description.push(image_path);

		} catch(err) {
			console.log(err.message);
		}

	}

	return array_images_description;
}


function makeImageCanvas(element, x, y) {
	var c   = document.getElementById("c-canvas");
	var ctx = c.getContext("2d");
	var img = document.getElementById("scream");
	ctx.drawImage(img,10,10);
}

workspace.pickImages.init();


