$(function(){
	var previewWrap = $('.preview');
	var baseURL = $('#baseURL').attr('href');
	var token = $('body').data('token');
	var js_cover = $('.controls-upload-cover');
	var bgLoader = $('.bgLoader');
	var ajaxLoader = $('.ajaxLoader');
	var cover = $('.profile-cover');

	var preview = function() {
     	// Hien thi anh cover do nguoi dung upload
		var show = function(img) {
		   previewWrap.html($('<img id="jcrop" src="/upload/covers/lg_' + img + '"/>'));
		   cover.remove();
		};
		return{
        	show: show
      };
	}();

	// Upload file
	 $('#fileupload').fileupload({
		url: baseURL + '/thanh-vien/cover?_token=' + token,
		dataType: 'json',
		done: function(e, data) {
			e.preventDefault();
			ajaxLoader.show();
			bgLoader.show();
			if (data.result === 0) {
				alert('Có lỗi xảy ra. Vui lòng kỉêm tra lại!');
				return;
			} else {
				setTimeout(function(){
					preview.show(data.result.data);
					js_cover.removeClass('btn_hidden');
					ajaxLoader.hide();
					bgLoader.hide();
				}, 3000);
			}
		},
	 }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
});