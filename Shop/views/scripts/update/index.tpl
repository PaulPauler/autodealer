<?=$this->content?>
<script type="text/javascript">
$(document).ready(function() {
	inputDefault(".input input");
	cart.GetInstance()
		.loadProducts([<?=implode(",", $this->soft_ids)?>])
		.show("table .action");
});
</script>