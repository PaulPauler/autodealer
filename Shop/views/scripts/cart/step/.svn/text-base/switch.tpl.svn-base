<h1>Оформление заказа</h1>
<?=$this->partial("cart/menu.tpl", $this)?>
<div class="block_grey short_form tmargin">
        <p class="nomargin">При выборе "Оформить заказ без регистрации", Вы теряете возможность следить за состоянием подготовки Своего(их) заказа(ов) и ведения статистики по всем осуществленным Вами ранее заказам.</p>
        <form method="post" action="<?=Location::GetInstance()?>" style="margin-left: 30px;" class="form_default">
            <div class="tmargin"><label><input type="radio" name="switch" value="1" style="margin-right: 5px"/> Выполнить вход в систему</label></div>
            <div style="margin-top: 5px"><label><input type="radio" name="switch" value="2" style="margin-right: 5px"/> Зарегистрироваться</label></div>
            <div style="margin-top: 5px"><label><input type="radio" name="switch" value="3" style="margin-right: 5px"/> Оформить заказ без регистрации</label></div>
            <div class="tmargin">
                <input type="submit" value="Продолжить" class="green" onclick="order.GetInstance().skip_submit(true);"/>
            </div>
        </form>
</div>
