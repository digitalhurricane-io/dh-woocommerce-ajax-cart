I made this ajax cart plugin because all the other 'ajax' cart plugins didn't actually use ajax.

They just hid the 'update cart' button and triggered a click event when you changed the quantity.

If you outputted the cart on a page other than the cart page, and you clicked 'update cart', you would be redirected to the cart page.

Thus the need for an ajax cart plugin that actually uses ajax.

Scripts are currently only enqueued on the checkout and cart pages. 

If you need to use the cart on some other page, you'll have to change that. An admin setting would be nice.