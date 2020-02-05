(function( $ ) {
	'use strict';

	$(document).ready(function() {

		// watch for X link click, when user want to completely remove item from cart
		const xLinks = $('td.product-remove > a');
		xLinks.each(function() {
			const xLink = $(this);

			// remove item on click
			xLink.on('click', (e) => {
				e.preventDefault();
				removeItem(xLink);
			});
		});

		// watch for when item quantity is adjusted
		var timeout;
		$("div.woocommerce").on("change keyup mouseup", "input.qty, select.qty", function () { // keyup and mouseup for Firefox support
			if (timeout != undefined) clearTimeout(timeout); //cancel previously scheduled event
			if ($(this).val() == "") return; //qty empty, instead of removing item from cart, do nothing
			timeout = setTimeout(function () {
				console.log($('form').serializeArray());
				try {
					ajaxPromise({
						type: 'POST',
						url: '/wp-admin/admin-ajax.php?action=dhaj_wc_adjust_cart',
						data: $('form').serialize()
					})
				} catch(e) {
					console.log(e);
				} 

			}, 400);
		});
	});



	async function removeItem(xLink) {
		const linkUrl = new URL(xLink[0].href);
		const urlParams = new URLSearchParams(linkUrl.search);
		const cartKey = urlParams.get('remove_item');
		const nonce = urlParams.get('_wpnonce');
		console.log('nonce: ', nonce);

		const successful = await removeItemRequest(cartKey, nonce);
		if (!successful) return;

		const tableRow = xLink.parent().parent();
		tableRow.remove();
	}

	async function removeItemRequest(cartKey, nonce) {
		const url = '/wp-admin/admin-ajax.php?action=dhaj_wc_remove_item';

		const data = [
			{ name: '_wpnonce', value: nonce },
			{ name: 'cartKey', value: cartKey }
		]

		let res;
		try {
			res = await ajaxPromise({
				type: "POST",
				url: url,
				data: data,
			});
		} catch (e) {
			console.log(e);
			return false;
		}
		console.log('res: ', res);

		if (res && res.success) {
			console.log('success');
			return true;
		}
		console.log('no success');
		return false;
	}

	function ajaxPromise(options) {
		return new Promise((resolve, reject) => {
			$.ajax(options).done(resolve).fail(reject);
		});
	}

})( jQuery );

