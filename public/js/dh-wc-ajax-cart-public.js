(function( $ ) {
	'use strict';

	$(document).ready(function() {

		coupon();

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

			timeout = setTimeout(async function () {
				console.log($('form').serializeArray());

				let res;
				try {
					res = await ajaxPromise({
						type: 'POST',
						url: '/wp-admin/admin-ajax.php?action=dhaj_wc_adjust_cart',
						data: $('form').serialize()
					})
				} catch(e) {
					console.log(e);
				} 
				console.log('res: ', res);

				// res.data contains the new total
				if (res && res.success) {
					
					// this try/catch is specific to a theme I'm making right now, with a modified cart template.
					// default cart template doesn't have a total field
					try {
						$('#cart_total_td > strong > span').text(numberWithCommas(res.data)); // change cart total
						$('#cart_total_td > strong > span').prepend('<span class="woocommerce-Price-currencySymbol">$</span>');
						
					} catch(e) {
						console.error(e);
					}
					
					updateSubtotals();
					
				}

			}, 400);
		});
	});

	// submit coupon code with ajax
	function coupon() {
		const btn = $('button[name="apply_coupon"]');
		console.log('coupon btn: ', btn);
		btn.unbind();

		btn.on('click', function(e) {
			e.preventDefault();

			var data = {
				coupon_code: $('#coupon_code').val(),
				security: dh_wc_vars.apply_coupon_nonce
			};

			const url = '/wp-admin/admin-ajax.php?action=dhaj_wc_apply_coupon';

			$.post(url, data).done(function (res) {

				if (res && !res.success) {
					console.log('failed coupon request');
					return;
				}

				const messageBox = $('#pnp-coupon-message');

				const notices = res.data.notices;
				const total = res.data.total;

				if (notices.includes('does not exist')) {
					messageBox.text("Coupon does not exist");

				} else if (notices.includes('code already applied')) {
					messageBox.text("Coupon already applied");

				} else if (notices.includes('code applied successfully')) {
					messageBox.text("Coupon applied successfully");
				}
				
				updateTotal(total);
			});
		});
	}

	// loop through rows and recalculate subtotals
	function updateSubtotals() {
		let tableRows = $('table.cart > tbody > tr.cart_item');
		console.log('tableRows: ', tableRows);

		tableRows.each(function() {
			const tr = $(this);

			const quantity = tr.find('.qty').val();
			console.log('quantity: ', quantity);

			const price = tr.find('.product-price .woocommerce-Price-amount').text().slice(1); // slice to take off $
			console.log('price: ', price);

			const newSubtotal = numberWithCommas((quantity * parseFloat(price)).toFixed(2));
			
			if (parseFloat(newSubtotal) == 0) {
				tr.remove();
				return;
			}

			tr.find('.product-subtotal .woocommerce-Price-amount').text(newSubtotal);
			tr.find('.product-subtotal .woocommerce-Price-amount').prepend('<span class="woocommerce-Price-currencySymbol">$</span>');
			
		});
			
	}

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

			// this try/catch is specific to a theme I'm making right now, with a modified cart template.
			// default cart template doesn't have a total field
			// const updatedTotal = numberWithCommas(parseFloat(res.data).toFixed(2));
			// try {
			// 	$('#cart_total_td > strong > span').text(updatedTotal); // change cart total
			// 	$('#cart_total_td > strong > span').prepend('<span class="woocommerce-Price-currencySymbol">$</span>');

			// } catch (e) {
			// 	console.error(e);
			// }
			updateTotal(res.data);

			return true;
		}
		console.log('no success');
		return false;
	}

	function updateTotal(total) {
		const updatedTotal = numberWithCommas(parseFloat(total).toFixed(2));
		// this try/catch is specific to a theme I'm making right now, with a modified cart template.
		// default cart template doesn't have a total field
		try {
			$('#cart_total_td > strong > span').text(updatedTotal); // change cart total
			$('#cart_total_td > strong > span').prepend('<span class="woocommerce-Price-currencySymbol">$</span>');

		} catch (e) {
			console.error(e);
		}
	}

	function ajaxPromise(options) {
		return new Promise((resolve, reject) => {
			$.ajax(options).done(resolve).fail(reject);
		});
	}

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

})( jQuery );

