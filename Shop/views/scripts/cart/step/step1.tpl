<?
$cart = Cart::GetInstans();
?>
<h1>Ваша корзина</h1>

<?=$this->partial("cart/menu.tpl", $this)?>

<p>Здесь Вы можете отредактировать свой заказ и перейти к оформлению. Также Вы можете <a href="shop">перейти в интернет-магазин и продолжить покупки</a>. Вернуться в «Корзину» Вы можете с любой страницы сайта.</p>
<p>Если Вам нужно заказать <b class="red">несколько одинаковых товаров</b>, пожалуйста, укажите это в комментариях к заказу, либо свяжитесь с менеджером:<br /> по телефону (343) 268 27 57<br />через online-консультант.</p>
<table class="cart_table tmargin">
    <tr>
        <th colspan="2">Наименование</th>
        <th>Цена</th>
        <th></th>
    </tr>
    <?
    showItem($cart);
    $discount = $cart->getFullPriceWithoutDiscount() - $cart->getFullPrice();
    ?>
    <tr>
        <td colspan="2" class="tright">Общая скидка:</td>
        <td class="center green"><b><?=number_format($discount, "0", ",", " ")?> руб.</b></td>
        <td rowspan="2"></td>
    </tr>
    <tr>
        <td colspan="2" class="tright">Итого без учета доставки и скидки за способ оплаты:</td>
        <td class="center red"><b><?=number_format($cart->getFullPrice(), "0", ",", " ")?> руб.</b></td>
    </tr>
    <tr>
        <td colspan="4" class="tright">
            <a href="shop/soft" class="title_blue" style="margin-right: 15px;">Продолжить покупки</a>
            <a href="shop/cart/2" onclick="order.GetInstance().go(this); return false;" class="btn_big_green_bg1"><span class="btn_big_green_bg2">ОФОРМИТЬ ЗАКАЗ</span></a>
        </td>
    </tr>
</table>


<?
function showItem($rows, $spec = false) {
    foreach ($rows as $item) {
        $isSpecSoft = $item->isSpecSoft();
        $class_new_box = false;
        if (stripos($item->path_img, "images/design/box") !== false) $class_new_box = true;
        if ($isSpecSoft) {
            ?>
            <tr class="spec_color">
                <td colspan="4">
                    <b>Спецпредложение: <?=$item->name?></b><br />
                    На данное сочетание модулей действует специальная цена!
                </td>
            </tr>
            <? showItem($item->children, $isSpecSoft) ?>
            <tr class="spec_color vtop">
                <td colspan="2" class="vmiddle"><b>Спецпредложение: <?=$item->name?></b></td>
                <td class="center nowrap">
                    <s><?=number_format($item->getPriceWithoutDiscount(), "0", ",", " ")?> руб.</s><br />
                    <span class="green">скидка <?=number_format($item->getDiscount(), "0", ",", " ")?> руб.</span><br />
                    <span class="price"><?=number_format($item->getPriceWithCount(), "0", ",", " ")?> руб.</span>
                </td>
                <td></td>
            </tr>
            <?
            continue;
        }
        ?>
        <tr>
            <? if ($spec) { ?>
                <td class="spec_color" style="padding: 6px;">
                    <img alt="Спецпредложение" src="images/design/spec_text.png" width="12" height="110">
                </td>
            <? } ?>
            <td colspan="<?=$spec ? "1" : "2"?>">
                <img src="<?=$item->path_img?>" alt="<?=$item->name?>" class="<?=$class_new_box ? "soft_icon" : "page_icon"?>"/>
                <div style="overflow: hidden;">
                    <?=$item->text?>
                </div>
            </td>
            <td class="center nowrap vtop">
                <?
                $price = $item->getPriceWithCount();
                $discount = $item->getDiscount();
                $price_text = number_format($price, "0", ",", " ")." руб.";
                if ($spec or $discount != 0) {
                    if (!$spec) {
                        ?>
                        <s><?=number_format($item->getPriceWithoutDiscount(), "0", ",", " ")?> руб.</s><br />
                        <span class="green">скидка <?=number_format($discount, "0", ",", " ")?> руб.</span><br />
                        <span class="price"><?=$price_text?></span>
                        <?
                    }
                    else echo "<s>".number_format($item->getPriceWithoutDiscount(), "0", ",", " ")."</s> руб.";
                }
                else echo '<span class="price">'.$price_text.'</span>';
                ?>
            </td>
            <td class="vtop center">
                <img src="images/design/icon/14/close.png" alt="delete" width="14" height="14" class="delete" onclick="order.GetInstance().delete_soft(<?=$item->id?>)"/>
            </td>
        </tr>
        <?
    }
}
?>
