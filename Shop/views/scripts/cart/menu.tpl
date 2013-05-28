<div class="block">
    <div class="block_top"><div class="b_lt"></div><div class="b_bt"></div><div class="b_rt"></div></div>
    <div class="block_content_left">
        <div class="block_content_right">
            <div class="block_content">
                <?
                $items = array("Корзина", "Данные покупателя", "Способы доставки и оплаты", "Подтверждение заказа");
                $html = array();
                foreach ($items as $i => $item) {
                    if (($i + 1) == $this->step) $html[] = '<span class="btn_bg1"><span class="btn_bg2">'.$item.'</span></span>';
                    elseif ($i < $this->last_step) $html[] = '<span><a href="shop/cart/'.($i + 1).'" onclick="order.GetInstance().go(this); return false;" class="back">'.$item.'</a></span>';
                    else $html[] = '<span style="color: #999999;">'.$item.'</span>';
                }
                echo implode("<span>&rarr;</span>", $html);
                ?>
            </div>
        </div>
    </div>
    <div class="block_bottom"><div class="b_lb"></div><div class="b_bb"></div><div class="b_rb"></div></div>
</div>
