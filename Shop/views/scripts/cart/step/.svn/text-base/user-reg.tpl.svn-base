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
            <div class="red" id="messageKey" style="margin-top: 5px;"></div>
        </div>
    <? } ?>

    <div class="tmargin"><span class="title">Получатель</span> <span class="red" style="margin-left: 20px;" id="message_org"><?=$this->message_org ? $this->message_org : ""?></span></div>
    <ul class="none tmargin" id="order_organization">
        <?
        foreach ($this->orgs as $row) {
            $id = $row["id"]
            ?><li><label><input type="radio" name="org" value="<?=$id?>" <?=(isset($this->post["org"]) and $this->post["org"] == $id) ? 'checked="checked"' : ""?>/> <?=$row["name"]?></label></li>
        <?
        }
        ?>
    </ul>
    <div class="tmargin"><a href="cabinet/organization" onclick="user_add_org.GetInstance().show(); return false;">Добавить получателя</a></div>
    
    <div class="tmargin"><span class="title">Адреса</span> <span class="red" style="margin-left: 20px;" id="message_address"><?=$this->message_address ? $this->message_address : ""?></span></div>
    <ul class="none tmargin" id="order_address">
        <?
        foreach ($this->address as $row) {
            $id = $row["address_id"];
            $address = "{$row["index"]} {$row["country_name"]} {$row["region_name"]} {$row["city_name"]} {$row["address"]}"
            ?><li><label><input type="radio" name="address" value="<?=$id?>" <?=(isset($this->post["address"]) and $this->post["address"] == $id) ? 'checked="checked"' : ""?>/> <?=$address?></label></li>
        <?
        }
        ?>
    </ul>
    <div class="tmargin"><a href="cabinet/address" onclick="user_add_address.GetInstance().show(); return false;">Добавить адрес</a></div>

    <div class="title tmargin">Контактная информация</div>
    <table class="table_form">
        <tr>
            <td class="label">Телефон <span class="red">*</span></td>
            <td>
                <input type="text" class="field_text" name="phone" value="<?=(isset($this->post["phone"])) ? $this->post["phone"] : UserInfo::GetInstans()->phone?>"/>
                <p class="nomargin red" id="message_phone"></p>
            </td>
        </tr>
        <tr>
            <td class="label vtop">E-Mail <span class="red">*</span></td>
            <td>
                <input type="text" class="field_text" name="email" value="<?=(isset($this->post["email"])) ? $this->post["email"] : UserInfo::GetInstans()->email?>"/>
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
