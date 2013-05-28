<div id="cart">
<?=$this->partial($this->tpl, $this);?>
</div>
<script type="text/javascript">
<!--

$(document).ready(function() {
    order.GetInstance()
        .loadCart(<?=$this->cart?>)
        .loadSale(<?=$this->sale_js?>)
        .loadPayment(<?=$this->payment?>)
        .loadDelivery(<?=$this->delivery?>)
        .setUser(<?=UserInfo::GetInstans() ? "true" : "false"?>)
        .setDeliveryId(<?=isset($this->post["delivery"]) ? $this->post["delivery"] : 0?>)
        .setPaymentId(<?=isset($this->post["payment"]) ? $this->post["payment"] : 0?>);
});
    

//-->
</script>
