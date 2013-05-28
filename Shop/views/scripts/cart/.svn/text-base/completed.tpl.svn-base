<?
$table = new Order_View();
$row = $table->fetchRow("id = $this->order_id");
?>
<h1>Спасибо, Ваш заказ принят!</h1>
<?if ($row->payment == Order::QUITTANCE or $row->payment == Order::BANK) {
    $path = $text = "";
    if ($row->payment == Order::QUITTANCE) {
        $path = "blank";
        $text = "квитанцию СберБанка";
    }
    if ($row->payment == Order::BANK) {
        $path = "invoice";
        $text = "счет";
    }
    ?><p>Распечатайте <a href="<?=$path."?".$row->hash?>" target="_blank" style="font-weight: bold;"><?=$text?></a> сейчас</p>
    <script type="text/javascript">
        window.open(location.protocol+ "//" +location.hostname + "/<?=$path."?".$row->hash?>", "Бланк", "dependent");
    </script>
<?}
if ($row->payment == Order::WEBMONEY) {
    if ($this->errorWM) {
        $tInfo = new Info();
        $rInfo = $tInfo->fetchRow("id = 1");
        ?>
        <p><span class="red">Внимание!</span> При выставление счета возникла ошибка. Вы можете оплатить в автоматическом режиме.</p>
        <p>Оплата в автоматическом режиме:<br />
        <a href="wmk:payto?Purse=R314425391951&Amount=<?=$row->price?>&Desc=Оплата%20заказа%20№%20<?=$row->num_order?>&BringToFront=Y">Оплата заказа №<?=$row->num_order?> в WMR - Российсие рубли</a><br />
        <a href="wmk:payto?Purse=Z699586435804&Amount=<?=round($row->price/$rInfo->zwm, 2)?>&Desc=Оплата%20заказа%20№%20<?=$row->num_order?>&BringToFront=Y">Оплата заказа №<?=$row->num_order?> в WMZ - Доллары США</a><br />
        <a href="wmk:payto?Purse=E522043845182&Amount=<?=round($row->price/$rInfo->ewm, 2)?>&Desc=Оплата%20заказа%20№%20<?=$row->num_order?>&BringToFront=Y">Оплата заказа №<?=$row->num_order?> в WME - Евро</a><br />
        <a href="wmk:payto?Purse=G374438133120&Amount=<?=round($row->price/$rInfo->gwm, 2)?>&Desc=Оплата%20заказа%20№%20<?=$row->num_order?>&BringToFront=Y">Оплата заказа №<?=$row->num_order?> в WMG - Золото</a>
        </p>
        <?
    }
}
if ($this->errorQiwi > 0) {
    ?><p><span class="red">Внимание!</span> Счет не был доставлен платежной системе QIWI. Менеджер свяжется с вами.</p><?
}
?>
<p>Если Вы еще не зарегистрировались на нашем сайте, то можете сделать это сейчас.<a href="registration"> Зарегистрироваться</a></p>
<p>Регистрация позволит Вам:</p>
<ul class="list_nomargin">
    <li>получать подробный отчёт по Вашим будущим заказам;</li>
    <li>право в получении консультации в кротчайшие сроки;</li>
    <li>право продления лицензии без дополнительных звонков в офис Компании «АвтоДилер»;</li>
    <li>периодически получать на свой почтовый ящик информацию о новинках на рынке программных продуктов Компании «АвтоДилер»;</li>
    <li>получать новости сайта www.autodealer.ru;</li>
    <li>возможность участвовать в партнерской программе Компании «АвтоДилер».</li>
</ul>
