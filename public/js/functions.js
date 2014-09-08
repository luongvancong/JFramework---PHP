function addCommas(nStr){
	nStr += ''; x = nStr.split(',');	x1 = x[0]; x2 = ""; x2 = x.length > 1 ? ',' + x[1] : ''; var rgx = /(\d+)(\d{3})/; while (rgx.test(x1)) { x1 = x1.replace(rgx, '$1' + ',' + '$2'); } return x1 + x2;
}

/**
 * Thay đổi kiểu search trên header
 *
 * @param  object obj
 * @param  string type
 * @return void
 */
function changeSearchType() {
	var $this = $(this);
	$('.type-search-active .active').text($this.text());
	$('#inp-searchtype-hidden').val($this.data('id'));
	console.log($this.parents('.type-search-hidden'));
	$this.parents('.type-search-hidden').toggleClass("hidden");
	$('.search-bar input[type="text"]').focus();
}


function changeQuantityProductCart(obj) {
	var $this = $(obj);
	$.ajax({
		url : '/ajax/ajaxAddToCart',
		type : 'POST',
		data : {
			"_token" : document.getElementById("_token").value,
			"quantity" : $this.val(),
			"product_id" : $this.data('pid'),
			"product_child_id" : $this.data('pcid')
		},
		success : function(data) {
			if(data) {
				window.location.reload();
			}
		}
	});
}