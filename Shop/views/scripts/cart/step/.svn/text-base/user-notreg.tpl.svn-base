<h1>Оформление заказа</h1>
<?=$this->partial("cart/menu.tpl", $this)?>
<form action="<?=Location::GetInstance()->get(-1)?>/<?=($this->step + 1)?>" method="post">

    <? if (!Cart::GetInstans()->isProdlenie) {?>
        <div class="title tmargin">Данные о программе</div>
        <div class="table_form">
            Укажите номер usb-ключа защиты, если Вы уже используете хотя бы один модуль системы "АвтоДилер".
            <div id="issetKey" style="margin-top: 5px;">
                Введите номер ключа: 
                AD-<input type="text" class="field_text" value="<?=$this->post["key"] ? $this->post["key"] : ""?>" name="key" style="width: 100px;border: 2px solid #FFCD42;"/>
            </div>
            <div class="red" id="messageKey" style="margin-top: 5px;">
                <p class="nomargin red" id="message_key"></p>
            </div>
        </div>
    <? } ?>
    
    <div class="title tmargin">Получатель</div>

    <table class="table_form">
        <tr>
            <td class="label">Страна <span class="red">*</span></td>
            <td>
                <input type="text" class="field_text" name="country" value="<?=(isset($this->post["country"])) ? $this->post["country"] : "Россия"?>"/>
                <p class="nomargin red" id="message_country"></p>
            </td>
        </tr>
        <tr>
            <td class="label">Регион <span class="red">*</span></td>
            <td>
                <input type="text" class="field_text" name="region" value="<?=(isset($this->post["region"])) ? $this->post["region"] : ""?>"/>
                <p class="nomargin red" id="message_region"></p>
            </td>
        </tr>
        <tr>
            <td class="label">Организация или ФИО <span class="red">*</span></td>
            <td>
                <input type="text" class="field_text" name="org" value="<?=(isset($this->post["org"])) ? $this->post["org"] : ""?>"/>
                <p class="nomargin red" id="message_org"></p>
            </td>
        </tr>
        <tr>
            <td class="label">Контактное лицо</td>
            <td><input type="text" class="field_text" name="contact" value="<?=(isset($this->post["contact"])) ? $this->post["contact"] : ""?>"/></td>
        </tr>
        <tr>
            <td class="label">Адрес доставки <span class="red">*</span></td>
            <td>
                <input type="text" class="field_text" name="address" value="<?=(isset($this->post["address"])) ? $this->post["address"] : ""?>"/>
                <p class="nomargin red" id="message_address"></p>
            </td>
        </tr>
        <tr>
            <td class="label">Телефон <span class="red">*</span></td>
            <td>
                <input type="text" class="field_text" name="phone" value="<?=(isset($this->post["phone"])) ? $this->post["phone"] : ""?>"/>
                <p class="nomargin red" id="message_phone"></p>
            </td>
        </tr>
        <tr>
            <td class="label vtop">E-Mail <span class="red">*</span></td>
            <td>
                <input type="text" class="field_text" name="email" value="<?=(isset($this->post["email"])) ? $this->post["email"] : ""?>"/>
                <p class="nomargin red" id="message_email"></p>
                <div style="margin-top: 5px;"><label><input type="checkbox" name="subscribe" <?=(isset($this->post["subscribe"])) ? 'checked="checked"' : ""?>/> Подписаться на рассылку новостей</label></div>
            </td>
        </tr>
    </table>
    
    <table class="cart_table tmargin">
        <tr>
            <th>Наименование</th>
            <th>Сумма</th>
        </tr>
        <?
        foreach (Cart::GetInstans() as $item) {
            ?>
            <tr>
                <td><?=$item->name?> (<?=$item->label?>)</td>
                <td class="price center nowrap" style="width: 1%"><?=number_format($item->getPriceWithCount(), 0, ",", " ")?> руб.</td>
            </tr>
            <?
        }
        ?>
        <tr>
            <td class="tright red">Итого:</td>
            <td class="red price center nowrap"><?=number_format((Cart::GetInstans()->getFullPrice()), 0, ",", " ")?> руб.</td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="btn_big_green_bg1" style="float: right;">
                    <input type="submit" value="Все правильно"/>
                    <span class="btn_big_green_bg2">ВСЕ ПРАВИЛЬНО</span>
                </div>
                <a href="<?=Location::GetInstance()->get(-1)?>/<?=($this->step - 1)?>" onclick="order.GetInstance().go(this); return false;" class="btn_big_white_bg1 back"><span class="btn_big_white_bg2">ВЕРНУТЬСЯ</span></a>
            </td>
        </tr>
    </table>
    
</form>
