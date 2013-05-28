<h1>Оформление заказа</h1>
<?=$this->partial("cart/menu.tpl", $this)?>

<form action="<?=Location::GetInstance()->get(-1)?>/<?=($this->step + 1)?>" method="post">


    <table class="tmargin">
        <tr>
            <td colspan="2"><span class="title">Доставка</span> <span class="red" style="margin-left: 20px;" id="message_delivery"></span></td>
        </tr>
        <tr>
            <td class="vtop nowrap">
                <?
                if (!Cart::GetInstans()->isPhysicalDelevery) {
                    ?>По электронной почте.<?
                }
                else {
                    ?><ul class="none"><?
                    foreach (Order::$delivery as $key => $delivery) {
                        if ($delivery["isElectronicDelivery"]) continue;
                        ?><li><label>
                            <input 
                                type="radio" 
                                name="delivery" 
                                value="<?=$key?>"
                                onclick="orderInterface.changeHelpDelivery(this)" 
                                <?=(isset($this->post["delivery"]) and $this->post["delivery"] == $key) ? 'checked="checked"' : ""?>/> 
                                <?=$delivery["name"]?>
                        </label></li><?
                    }
                    ?></ul><?
                }
                ?>
            </td>
            <td class="vtop">
                <div id="helpDelivery1" class="help" <?=$this->post["delivery"] == 1 ? 'style="display: block;"' : ""?>>Срок доставки 7-21 дней (зависит от региона). Стоимость доставки 250 руб.</div>
                <div id="helpDelivery2" class="help" <?=$this->post["delivery"] == 2 ? 'style="display: block;"' : ""?>>Срок доставки 2-7 дней (зависит от региона). Стоимость доставки 850 руб.</div>
                <div id="helpDelivery3" class="help" <?=$this->post["delivery"] == 3 ? 'style="display: block;"' : ""?>>Производится из офиса Компании при наличии доверенности или печати организации.</div>
            </td>
        </tr>
        
        <?if (Cart::GetInstans()->isElectronicDelivery) {?>
            <tr>
                <td colspan="2">
                    <b>Файл лицензии</b> будет отправлен на Ваш электронный почтовый ящик <b><?=$this->post["email"]?></b> в течение одного рабочего дня с момента получения оплаты.
                </td>
            </tr>
        <? } ?>
        
        <tr>
            <td colspan="2"><span class="title tmargin" style="display: inline-block;">Оплата</span> <span class="red" style="margin-left: 20px;" id="message_payment"></span></td>
        </tr>
        <tr>
            <td class="vtop nowrap">
                <ul class="none">
                <?
                $showDiscount = false;
                foreach (Order::$payment as $key => $payment) {
                    $name = $payment["name"];
                    if ($payment["discount"] > 0 and !$showDiscount) {
                        $showDiscount = true;
                        echo '<li>';
                        
                        $time_start = mktime(0, 0, 0, 12, 30, 2012);
                        $time_end = mktime(0, 0, 0, 1, 9, 2013);
                        $time = time();
                        if ($time > $time_start and $time < $time_end) {
                            echo '<span class="red">Скидка 50% при заказе и оплате с 8 по 9 декабря 2012.</span><br /><a href="shop/action/emoney">Узнать условия акции</a>.';
                        }
                        else echo '<span class="red">Скидка 10% при оплате электронными деньгами:</span>';
                        echo '</li>';
                            //Скидка 50% при заказе и оплате с 8 по 9 декабря 2012. Узнать условия акции.
                        //echo () ? '<a href="shop" class="btn_big_green_bg1 tmargin"><span class="btn_big_green_bg2">Купить со скидкой 50%</span></a>' : '';
                    }
                    ?><li><label><input type="radio" name="payment" value="<?=$key?>" <?=$this->post["payment"] == $key ? 'checked="checked"' : ""?> onclick="orderInterface.changeHelpPayment(this)"/> <?=$name?></label></li><?
                }
                ?>
                </ul>
            </td>
            <td class="vtop">
                <div id="helpPayment1" class="help" <?=$this->post["payment"] == 1 ? 'style="display: block;"' : ""?>>
                        <a href="http://www.webmoney.ru/" target="_blank">WebMoney</a> После оплаты на Ваш адрес будет выслана ценная бандероль с программами и всей необходимой документацией.
                        <div class="delete paymentWebMoney">
                            <p>Введите свой <b>WMID</b> <input type="text" name="customerwmid" id="customerwmid" value="<?=$this->post["customerwmid"]?>" class="field_text"/></p>
                            <p>Выберети тип кошелька:</p>
                            <ul class="none" style="margin-top: 5px;">
                            <?foreach (WebMoney::$Currency as $key => $name) { ?>
                                <li><label><input type="radio" name="storepurse" value="<?=$key?>" <?=$this->post["storepurse"] == $key ? 'checked="checked"' : ''?>/> <?=$name["title"]?></label></li>
                            <? } ?>
                            </ul>
                            <script type="text/javascript">$("#customerwmid").mask("999999999999", {placeholder: ""});</script>
                        </div>
                        <p>Расчеты в валюте отличной от WMR производятся по курсу ЦБ РФ на день оплаты.</p>
                        <div class="delete">
                            <p><a href="http://www.webmoney.ru/rus/cooperation/legal/syagreement1.shtml" target="blank">Соглашение о трансфере имущественных прав цифровыми титульными знаками</a></p>
                            <p class="nomargin">Предлагаемые товары и услуги предоставляются не по заказу лица либо предприятия, эксплуатирующего систему WebMoney Transfer. Мы являемся независимым предприятием, оказывающим услуги, и самостоятельно принимаем решения о ценах и предложениях. Предприятия, эксплуатирующие систему WebMoney Transfer, не получают комиссионных вознаграждений или иных вознаграждений за участие в предоставлении услуг и не несут никакой ответственности за нашу деятельность.</p>
                            <p class="nomargin">Аттестация, произведенная со стороны WebMoney Transfer, лишь подтверждает наши реквизиты для связи и удостоверяет личность. Она осуществляется по нашему желанию и не означает, что мы каким-либо образом связаны с продажами операторов системы WebMoney.</p>          
                        </div>
                    </div>
                    <div id="helpPayment2" class="help" <?=$this->post["payment"] == 2 ? 'style="display: block;"' : ""?>>
                        <a href="http://money.yandex.ru/" target="_blank">Yandex.Деньги</a> После оплаты на Ваш адрес будет выслана ценная бандероль с программами и всей необходимой документацией.
                        <ul class="list">
                            <li>Откройте Ваш Интернет.Кошелёк</li>
                            <li>Нажмите кнопку "Отправить деньги"</li>
                            <li>Имя получателя: Компания АвтоДилер</li>
                            <li>Номер счета: 4100151290293</li>
                            <li>E-mail получателя: postmaster@autodealer.ru</li>
                            <li>В назначении платежа обязательно укажите имя плательщика и номер заказа.</li> 
                        </ul>
                    </div>
                    <div id="helpPayment3" class="help" <?=$this->post["payment"] == 3 ? 'style="display: block;"' : ""?>>
                        <b>Только для резидентов РФ.</b> Банковский перевод (безналичный расчёт) - На Ваш E-mail или факс будет выслан счет. После оплаты счёта и поступления средств на наш расчетный счет, на Ваш адрес будет выслана ценная бандероль с программами и всей необходимой документацией.
                    </div>
                    <div id="helpPayment4" class="help" <?=$this->post["payment"] == 4 ? 'style="display: block;"' : ""?>><b>Только для резидентов РФ.</b> Квитанция СберБанка - после оплаты квитанции и поступления средств на наш расчетный счет, на Ваш адрес будет выслана ценная бандероль с программами и всей необходимой документацией.</div>
                    <div id="helpPayment5" class="help" <?=$this->post["payment"] == 5 ? 'style="display: block;"' : ""?>>
                        <a href="https://w.qiwi.ru/" target="_blank">QIWI</a> Оплата заказа через терминалы платежной или с сайта системы <a href="https://w.qiwi.ru/" target="_blank">QiWi кошелек</a><br /><br />
                        <div class="delete">
                            Введите номер мобильного телефона&nbsp;&nbsp;
                            <input type="text" name="phone_for_qiwi" class="field_text" id="phone_for_qiwi" value="<?=$this->post["phone_for_qiwi"]?>"/>
                            <script>$("#phone_for_qiwi").mask("(999) 999-99-99", {placeholder: "_"});</script>
                        </div>
                    </div>
            </td>
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
                <td class="price nowrap center" style="width: 1%"><?=$price_delivery?> руб.</td>
            </tr>
            <?
        }
        else {
            ?>
            <tr id="trDelivery" style="display: none;">
                <td>Доставка: <span></span></td>
                <td class="price nowrap center" style="width: 1%"></td>
            </tr>
            <? 
        }
        $discount = Cart::GetInstans()->getFullPrice() - $full_price;
        ?>
        <tr id="itogDiscount" <?=$discount == 0 ? 'style="display: none"': ""?>>
            <td class="tright">Скидка за оплату: <span><?=isset($this->post["payment"]) ? Order::GetPaymentById($this->post["payment"], "name") : ''?></span></td>
            <td class="price green center nowrap" style="width: 1%"><?=number_format($discount, 0, ",", " ")?> руб.</td>
        </tr>
        <tr id="itog">
            <td class="red tright">Итого:</td>
            <td class="red price center nowrap" style="width: 1%"><?=number_format(($full_price + $price_delivery), 0, ",", " ")?> руб.</td>
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
