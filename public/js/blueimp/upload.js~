$(function() {
    var previewWrap = $('.preview');
    var jcrop_api;
    var baseURL = $('#baseURL').attr('href');
    var courseID = $('.createCourses').data('course');
    var token = $('body').data('token');
    var btnSave = $('.btnSave');
    var crop = $('#crop');

    var preview = function() {
        var loadCrop = function() {
            $('#jcrop').Jcrop({
                bgFade: true,
                onSelect: setCrop,
                bgOpacity: .2,
                minSize: [265, 135],
                maxSize: [265, 135],
                aspectRatio: 16 / 9
            }, function() {
                jcrop_api = this;
            });
        };

        var renderTemplate = function(item, data) {
            var source = item.html();
            var template = Handlebars.compile(source);
            data = typeof data !== 'undefined' ? data : {};
            return template(data);
        };

        var setCrop = function(c) {
            crop.find('input[name=x]').val(c.x);
            crop.find('input[name=y]').val(c.y);
        };

        var show = function(img) {
            previewWrap.html($('<img id="jcrop" src="' + img + '"/>'));
            loadCrop();
            btnSave.removeClass('hidden');
        };

        var cropThumbnail = function() {
            var input = crop.find('form').serialize();
            $.post(baseURL + '/quan-ly-tin-dang/' + courseID + '/crop-thumbnail', input, function(result) {
                if (result === 0) {
                    var message = {title: 'Có lỗi!', errors: [{message: result.message}]};
                    bootbox.alert(renderTemplate($('#showListErrorBootbox'), message));
                    return;
                } else {
                    jcrop_api.destroy();
                    btnSave.addClass('hidden');
                }
            });
        };
        return{
            show: show,
            cropThumbnail: cropThumbnail
        };
    }();

    var url = baseURL + '/quan-ly-tin-dang/' + courseID + '/thumbnail?_token=' + token;
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        done: function(e, data) {
            if (data.result === 0) {
                validator.title = "Có lỗi xảy ra!";
                bootbox.alert(base.renderTemplate($('#showListErrorBootbox'), validator));
                return;
            } else {
                preview.show(data.result.data);
                crop.find('input[name=name]').val(data.result.data);
            }
        },
        progressall: function(e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('.preview').find('.process').text(progress + '%');
        }
    }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');

    btnSave.on('click', function() {
        preview.cropThumbnail();
    });

});
