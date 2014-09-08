$(function(){
	var baseURL = $('#baseURL').attr('href'); // đường dẫn site
	var token = $('body').data('token'); // token global site
	var profileWrap = $('#waaProfileCover'); // element parent include profile cover
	var waaCoverImageContainer = $('#waaCoverImageContainer');
	var coverImage = $('#coverImage');
	var fileUpload = $('.fileUpload'); // element upload file
	var coverLoader = $('.coverChangeThrobber'); // element loader when upload cover
	var coverBorder = $('.coverBorder'); // element when upload will disabel element parent cover
	var instructionWrap = $('.instructionWrap'); // element when upload success will show notifications to drag cover
	var coverPhotoImg = $('.coverPhotoImg'); // element image priew
	var profileInfor= $('.profile-user-info');
	var profilePicNotch = $('.profilePicNotch');
	var fileCover = $('#editCoverSave');

	var profileCover = function(){
		var show = function(img){
			if (img ==='') {
				return false;
			};
			profileWrap.addClass('waaEditCover');
			waaCoverImageContainer.find('img').attr('src', '/upload/covers/' + img).addClass('coverPhotoAppend');
			fileCover.find('input[name=image_cover]').val(img);
		};

		var dragCover = function(){
			if ($('#waaProfileCover').hasClass('waaEditCover')) {
				// Keo dieu chinh vi tri anh
				$('.waaEditCover').find('.coverPhotoImg').draggable({
					axis: "y",
					drag : function(e, ui){
						var wrapper = coverImage.offset(); // Toa do cua khung anh
						var offset = ui.helper.offset(); // Toa do vi tri anh hien tai
						var pImg = offset.top - wrapper.top; // Toa do anh di chuyen
						fileCover.find('input[name=photo_offset_y]').val(pImg + 1);
					}
				});
			};
		}
		return{
			show:show,
			dragCover:dragCover
		};
	}();

	// FileUpload
	$('#fileupload').fileupload({
		url: baseURL + '/thanh-vien/change-cover?_token=' + token,
		dataType: 'json',
		done :function(e, data){
			coverLoader.show();
			coverBorder.show();
			if (data.result === 0) {
				alert('Có lỗi xảy ra. Vui lòng kỉêm tra lại!');
				return;
			} else {
				setTimeout(function(){
					profileCover.show(data.result.data);
					instructionWrap.show();
					coverLoader.hide();
					coverBorder.hide();
					profileInfor.hide();
					profilePicNotch.show();
					$('.fileUpload').hide();
					profileCover.dragCover();
				},2000);
			}
		},
	 }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
});