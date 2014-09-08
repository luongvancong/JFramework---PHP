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



$(function() {

   // Align height of product's image
   //
   $('.top-board-item img').height($('.top-board-item li').width());

   // Warning when delete an item
   //
   $('.btn-delete-action').click(function(ev) {
      ev.preventDefault();
      var answer = confirm('Bạn có chắc chắn muốn xóa bản ghi này?');
      if (answer) return window.location.href = $(this).attr('href');
      else return false;
   });

   // Init editor
   //
   tinymce.init({
      selector: "textarea.editor",
      height: 200
   });


   // Init tooltip
   //
   $('.tt').tooltip();

   $('body').on('shown.bs.modal', function() {
      $('#shipping_name').focus();
   });

   // Create shipping method
   //
   $('#btn-create-shipping').click(function() {
      var _shippingName = $('#shipping_name').val();
      var _shippingDesc = $('#shipping_desc').val();

      if (_shippingName == "") {
         $('#shipping_name').parent().addClass('has-error');
         $('#shipping_name').next().removeClass('hidden');
         return false;
      } else {
         $('#shipping_name').parent().removeClass('has-error');
         $('#shipping_name').next().addClass('hidden');
      }

      $.ajax({
         type: 'POST',
         url: '/account/shipping/create',
         data: $('#f-shipping').serialize(),
         success: function(data) {
            $('#product_shipping').append('<option value="'+ data.id +'" selected>'+ data.name +'</option>');
            $('#create-shipping').modal('hide');
         }
      });

      return false;
   });

   $('#f-create-collection').submit(function() {
      var $name = $('#collection_name');
      var $des = $('#collection_des');

      if($name.val() == '') {
         alert('Vui lòng nhập tên bộ sưu tập');
         $name.focus();
         return false;
      }

      $.ajax({
         type: 'POST',
         url: '/account/board/ajax-create',
         data: $('#f-create-collection').serialize(),
         dataType: 'json',
         success: function(data) {
            if(data.code == 1) {
               $('#product_board').append('<option value="'+ data.id +'" selected>'+ data.name +'</option>');
            }
            $('#modal-create-collection').modal('hide');
         }
      });

      return false;
   });

   // Init slider
   //
   $('#collection-slider').bxSlider({
      pager: false,
      slideWidth: 100,
      minSlides: 5,
      maxSlides: 5,
      slideMargin: 5,
      nextSelector: '#slider-next',
      prevSelector: '#slider-prev',
      nextText: '<i class="fa fa-chevron-right"></i>',
      prevText: '<i class="fa fa-chevron-left"></i>',
      onSliderLoad: function(currentIndex) {
         var _marginImg = ($('#collection-slider li').height() - $('#collection-slider li img').height()) / 2;
         $('#collection-slider li img').css('top', _marginImg+'px');
      }
   });

   $('#collection-slider li').click(function(e) {
      e.preventDefault();
      var _imgSrc = $(this).find('img').attr('src');

      // Set preview
      //
      _imgSrc = _imgSrc.replace('sm_', 'lg_');
      $('#collection-view-port').find('img').attr('src', _imgSrc);

      // Set value
      //
      var _imgName = $(this).data('img');
      console.log(_imgName);
      $('#collection_image').val(_imgName);
   });

   // Duplicate product branch
   //
   // $('#btn-duplicate-product').click(function(e) {
   //    e.preventDefault();
   //    var productAdder = $('.product-item-adder').first().clone();
   //    $('.product-item-adder').last().after(productAdder[0]);
   //    // Init tooltip
   //    //
   //    $('.tt').tooltip();
   // });

   /**
    * Xóa 1 bộ sưu tập - Trang profile
    */
   $('.js-delete-board').on('click', function(e) {
      e.preventDefault();
      var $this = $(this);
      if(confirm('Bạn có chắc chắn muốn xóa Bộ sưu tập này')) {
         $.ajax({
            url : '/account/boards/ajax-delete',
            type : 'GET',
            dataType : 'json',
            data : {
               board_id : $this.data('bid'),
               _token : $this.data('token')
            },
            success : function(data) {
               if(data.code) {
                  $this.parents('tr').remove();
               }
               alert(data.message);
            }
         });
      }
   });

   /**
    * Active 1 bộ sưu tập - Trang profile
    */
   $('.js-active-board').on('click', function(e) {
      e.preventDefault();
      var $this = $(this);

      $.ajax({
         url : '/account/boards/ajax-active',
         type : 'GET',
         dataType : 'json',
         data : {
            board_id : $this.data('bid'),
            _token : $this.data('token')
         },
         success : function(data) {
            if(data.code) {
               if(data.status) {
                  $this.find('i').removeAttr("class").addClass("fa fa-check-square");

                  $('#text-status-board-'+$this.data('bid'))
                     .removeAttr("class")
                     .addClass("label label-success")
                     .text("Đang hoạt động");

               }else{
                  $this.find('i').removeAttr("class").addClass("fa fa-square-o");

                  $('#text-status-board-'+$this.data('bid'))
                     .removeAttr("class")
                     .addClass("label label-default")
                     .text("Ngưng hoạt động");
               }
            }else{
               alert(data.message);
            }
         }
      });

   });

});