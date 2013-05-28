<?=$this->content?>
<script type="text/javascript">
$(document).ready(function() {
	cart.GetInstance()
		.loadProducts([<?=implode(",", $this->soft_ids)?>])
		.loadBtn({
			cart_add: '<span class="btn_green_bg1 btn_add_to_cart">\
						<span class="btn_green_bg2">\
							<img src="images/design/icon/sundry/cart.png" alt="cart" class="cart_img">\
							Добавить\
						</span>\
					</span>',
			cart_delete: '<span class="btn_white_bg1 btn_delete_from_cart">\
							<span class="btn_white_bg2">\
								<img src="images/design/icon/sundry/cart.png" alt="cart" class="cart_img">\
								Удалить\
							</span>\
						</span>'
		})
		.show("table .btn");
});
</script>