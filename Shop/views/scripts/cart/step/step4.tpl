<h1>Оформление заказа</h1>
<?=$this->partial("cart/menu.tpl", $this)?>
<form action="<?=Location::GetInstance()?>" method="post">
    <table class="table_form">
        <?
        foreach ($this->info as $key => $value) {
            ?>
            <tr>
                <td class="label"><b><?=$key?></b></td>
                <td><?=$value?></td>
            </tr>
            <?
        }
        ?>
        <tr>
            <td class="label vtop"><b>Комментарий</b></td>
            <td><textarea rows="5" cols="80" name="description" class="field_text"></textarea></td>
        </tr>
    </table>
    
    <table class="cart_table tmargin">
        <tr>
            <th>Наименование</th>
            <th>Сумма</th>
        </tr>
        <?
        $full_price = 0;
        $sale = $this->sale;
        foreach (Cart::GetInstans() as $item) {
            $price = $item->getPriceWithCount();
            $price = $price * (1 - $sale->getPercent($item->id));
            $price -= ($sale->getDiscount($item->id) * $item->count);
            $full_price += $price
            ?>
            <tr>
                <td><?=$item->name?> (<?=$item->label?>)</td>
                <td class="price center nowrap"><?=number_format($item->getPriceWithCount(), 0, ",", " ")?> руб.</td>
            </tr>
            <?
        }
        $price_delivery = 0;
        if (isset($this->post["delivery"])) {
            $price_delivery = (int) Order::GetDeliveryById($this->post["delivery"], "price");
            ?>
            <tr id="trDelivery">
                <td>Доставка: <span><?=Order::GetDeliveryById($this->post["delivery"], "name")?></span></td>
                <td class="price center nowrap" style="width: 1%"><?=$price_delivery?> руб.</td>
            </tr>
            <?
        }
        else {
            ?>
            <tr id="trDelivery" style="display: none;">
                <td>Доставка: <span></span></td>
                <td class="price center nowrap" style="width: 1%"></td>
            </tr>
            <? 
        }
        $discount = Cart::GetInstans()->getFullPrice() - $full_price;
        ?>
        <tr id="itogDiscount" <?=$discount == 0 ? 'style="display: none"': ""?>>
            <td class="tright">Скидка за оплату: <span><?=isset($this->post["payment"]) ? Order::GetPaymentById($this->post["payment"], "name") : ''?></span></td>
            <td class="price green center nowrap"><?=number_format($discount, 0, ",", " ")?> руб.</td>
        </tr>
        <tr>
            <td class="red tright">Итого:</td>
            <td class="red price center nowrap"><?=number_format(($full_price + $price_delivery), 0, ",", " ")?> руб.</td>
        </tr>
        <tr>
            <td colspan="2">Я согласен(а) на обработку персональных данных (организации, фамилии, имени, отчества, адреса, телефона, факса, адреса электронной почты). Эти данные используются нами исключительно в целях выполнения Ваших заказов.</td>
        </tr>
        <tr>
            <td colspan="2">
                <!-- 
                <input type="submit" name="saveOrder" value="Потвердить заказ" style="float: right;"/>
                 -->
                <div class="btn_big_green_bg1" style="float: right;">
                    <input type="submit" name="saveOrder" value="ПОДТВЕРДИТЬ ЗАКАЗ" onclick="order.GetInstance().skip_submit(true);"/>
                    <span class="btn_big_green_bg2">ПОДТВЕРДИТЬ ЗАКАЗ</span>
                </div>
                <a href="<?=Location::GetInstance()->get(-1)?>/<?=($this->step - 1)?>" onclick="order.GetInstance().go(this); return false;" class="btn_big_white_bg1 back"><span class="btn_big_white_bg2">ВЕРНУТЬСЯ</span></a>
            </td>
        </tr>
    </table>
</form>
