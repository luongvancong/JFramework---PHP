$(function() {

   var totalTabsWidth = 82;
   var tabContainerWidth = $('#tab-container').width() - 50;

   // Toggle sub-menu on sidebar
   //
   $('.active-sub-menu').click(function(ev) {
      ev.preventDefault();
      var that = $(this);
      that.next().slideToggle(200);
      that.parent().addClass('sub-menu-actived');
   });

   // Toggle sidebar
   //
   $('.toggle-sidebar a').click(function(ev) {
      ev.preventDefault();
      $('#side-bar').toggleClass('sb-hidden');
      $('#main-page, #tab-container').toggleClass('resize');

      if ($('#side-bar').hasClass('sb-hidden')) {
         tabContainerWidth += 150;
      } else {
         tabContainerWidth -= 150;
      }
   });

   // Tab-control handler
   //
   $('.tab-control').click(function(ev) {
      ev.preventDefault();
   });

   // Tab-control handler: Slide left
   //
   $('#tab-control-left').click(function(ev) {
      var _leftPos = $('#tab-list').position().left - 50;
      if (totalTabsWidth > tabContainerWidth && Math.abs(_leftPos) <= (totalTabsWidth - tabContainerWidth)) {
         $('#tab-list').css('left', _leftPos + 'px');
      }
      return false;
   });

   // Tab-control handler: Slide right
   //
   $('#tab-control-right').click(function(ev) {
      var _leftPos = $('#tab-list').position().left + 50;
      if (_leftPos <= 22)
         $('#tab-list').css('left', _leftPos + 'px');

      return false;
   });

   // View/show tab content
   //
   $('.menu-item').click(function(ev) {
      ev.preventDefault();
      var tabId = $(this).attr('data-tab');

      // Active sidebar item
      //
      $('#side-bar ul li').removeClass('active');
      $(this).parent().addClass('active');

      // Nếu đã tồn tại tabId thì active
      //
      if ($('#tab-container').find('#'+tabId).length > 0) {
         // Active tab
         //
         $('#tab-list li').removeClass('active');
         $('#'+tabId).addClass('active');

         // Active iframe tab content
         //
         $('.tab-frame').addClass('frame-hidden');
         $('#iframe-'+tabId).removeClass('frame-hidden');
         return;
      }

      var newTab = $('<li>');

      // Add tab title
      //
      newTab.attr('id', tabId)
            .html('<span class="refresh-tab"><i class="fa fa-refresh"></i></span> <a href="#iframe-'+ tabId +'">' + $(this).find('span').text() + '</a> <button type="button" class="close" aria-hidden="true">&times;</button>');

      // Add tab item to tab container
      //
      $('#tab-list').append(newTab);

      // Hide old iframe tabs
      //
      $('.tab-frame').addClass('frame-hidden');

      // Hide tabs
      //
      $('#tab-list li').removeClass('active');

      // Show new tab
      //
      $('#'+tabId).addClass('active');

      // Append iframe tab content
      //
      $('#frame-container').append('<div id="iframe-'+ tabId +'" class="tab-frame"><iframe src="'+ $(this).attr('href') +'" frameborder="0" height="100%" width="100%"></div>');

      // Set width to iframe
      //
      $('#iframe-'+ tabId).height($(window).height() - 70);

      totalTabsWidth += newTab.width();
   });

   // Close a tab
   //
   $('#tab-list').on('click', '.close', function(ev) {
      ev.preventDefault();
      ev.stopPropagation();

      var tabId = $(ev.currentTarget).prev().attr('href');

      if ($(ev.currentTarget).parent().hasClass('active')) {
         // Active prev tab
         //
         $('#tab-list li').removeClass('active');
         $(ev.currentTarget).parent().prev().addClass('active');

         // Active prev iframe tab content
         //
         $('.tab-frame').addClass('frame-hidden');
         $($(ev.currentTarget).parent().prev().find('a').attr('href')).removeClass('frame-hidden');
      }

      // Remove tab
      //
      $(ev.currentTarget).parent().remove();

      // Decrease tab container width
      //
      totalTabsWidth -= $(tabId).width();

      // Remove iframe tab content
      //
      $(tabId).remove();
   });

   // Refresh a tab
   //
   $('#tab-list').on('click', '.refresh-tab', function(ev) {
      ev.preventDefault();
      ev.stopPropagation();

      var tabId = $(ev.currentTarget).next().attr('href');
      var _iframe = $(tabId).find('iframe');

      _iframe.attr('src', _iframe.attr('src'));
   });

   // Active a tab
   //
   $('#tab-list').on('click', 'a', function(ev) {
      ev.stopPropagation();
      ev.preventDefault();

      var tabId = $(ev.currentTarget).attr('href');

      // Nếu chưa đc active thì active
      //
      if (!$(ev.currentTarget).parent().hasClass('active')) {
         $('#tab-list').find('li').removeClass('active');
         $(ev.currentTarget).parent().addClass('active');
      }

      // Hiển thị tab-frame
      //
      $('.tab-frame').addClass('frame-hidden');
      $(tabId).removeClass('frame-hidden');
   });
});

/**
 * Preview image before upload
 * @param  event
 * @return img dom
 */
function fileSelect(evt) {
   if (window.File && window.FileReader && window.FileList && window.Blob) {
      var files = evt.target.files;
      var result = '';
      var file;
      for (var i = 0; file = files[i]; i++) {
         // if the file is not an image, continue
         if (!file.type.match('image.*')) {
            continue;
         }

         reader = new FileReader();
         reader.onload = (function (tFile) {
            return function (evt) {
               var div = document.createElement('div');
               div.className = "img-preview-wrapper";
               div.innerHTML = '<img class="img-preview" src="' + evt.target.result + '" />';
               $('.preview-uploader').html(div);
            };
         }(file));
         reader.readAsDataURL(file);
      }
   } else {
      alert('The File APIs are not fully supported in this browser.');
   }
}
