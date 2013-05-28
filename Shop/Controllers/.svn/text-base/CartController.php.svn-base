<?
class Shop_CartController extends Shop_Controllers {
    
    private $session_name = __CLASS__;
    private $current_step;
    
    public function init() {
        parent::init();
        $layout = $this->getHelper("layout");
        $layout->setLayout("index_without_left");
        Path::GetInstance()->add("Интернет-магазин");
        Path::GetInstance()->add("Корзина");
        
        $step = Location::GetInstance()->getLast();
        if (ctype_digit($step)) $this->getRequest()->setActionName("index");
    }
    
    
    public function indexAction() {
        
        
        if (Cart::GetInstans()->getFullPrice() == 0) {
            $this->view->title = "Ваша корзина";
            $this->render("empty-cart");
            return;
        }
        
        $this->view->title = "Оформление заказа";
        $this->view->headScript()->appendFile("js/order.js");
        $this->view->headScript()->appendFile("js/user.cabinet.js");
        
        $session = new Zend_Session_Namespace($this->session_name);
        if (!$session->__isset("step")) $session->step = 1;
        $step = (int) Location::GetInstance()->getLast();
        if ($step < 1) $step = 1;
        if ($step > 1 and Cart::GetInstans()->getFullPrice() == 0) $this->_redirect(Location::GetInstance()->get(2));
        if ($step > $session->step + 1) $step = $session->step;
        elseif ($step > $session->step) $session->step = $step;
        
        $this->current_step = $step;
        
        $this->view->last_step = $session->step;
        $this->view->step = $step;
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            if (!empty($post) and !isset($post["switch"])) $this->savePost();
            if (isset($post["saveOrder"])) $this->order();
        }
        
        $post = $this->clearPost($this->getPost());
        
        $code = "";
        $payment = 0;
        if (isset($post["code"])) $code = $post["code"];
        if (isset($post["payment"])) $payment = $post["payment"];
        $this->view->sale = Sale::Factory($code, $payment);
        $this->view->post = $post;
        
        $script = "step";
        switch ($step) {
            case 1: {
                $this->view->title = "Ваша корзина";
                $script .= "/step$step";
                break;
            }
            case 2: {
                if (UserInfo::GetInstans()) {
                    $script .= "/user-reg";
                    if (empty($this->view->post)) {
                        $this->view->post["subscribe"] = "on";
                        $this->view->post["email"] = UserInfo::GetInstans()->email;
                    }
                    $table = new UserInfo_Table_Organization();
                    $this->view->orgs = $table->getOrganization(UserInfo::GetInstans()->id);
                    $table = new UserInfo_Table_Address();
                    $this->view->address = $table->getAddressView(UserInfo::GetInstans());
                }
                else {
                    if ($session->__isset("switch")) $script .= "/user-notreg";
                    else {
                        $_post = $this->getRequest()->getPost();
                        if (isset($_post["switch"])) {
                            if ($_post["switch"] == 1) $this->_redirect("login");
                            if ($_post["switch"] == 2) $this->_redirect("registration");
                            if ($_post["switch"] == 3) {
                                $session->switch = true;
                                $this->_redirect(Location::GetInstance());
                            }
                        }
                        $script .= "/switch";
                    }
                }
                break;
            }
            
            case 4: {
                $array_info = array();
                if (UserInfo::GetInstans()) $array_info = $this->getInfoUser();
                else $array_info = $this->getInfoNonUser();
                
                $array_info = array_merge(
                        $array_info,
                        $this->getInfoContact(),
                        $this->getInfoPayment()
                );
                $this->view->info = $array_info;
                $script .= "/step$step";
                break;
            }
            
            default: {
                $script .= "/step$step";
            }
        }
        if ($this->getRequest()->isXmlHttpRequest()) {
            Zend_Controller_Action_HelperBroker::removeHelper('Layout');
            $this->render($script);
        }
        else {
            $this->view->tpl = "cart/$script.tpl";
            $this->indexNotAjax();
        }
    }
    
    
    private function indexNotAjax() {
        // Выбор данных доставки
        $this->view->delivery = array();
        foreach (Order::$delivery as $key => $values) {
            $this->view->delivery[$key]["name"] = $values["name"];
            $this->view->delivery[$key]["price"] = $values["price"];
        }
        
        // Загрузка данных из корзины
        $this->view->cart = array();
        $this->view->cart["electronicDelivery"] = Cart::GetInstans()->isElectronicDelivery;
        $this->view->cart["soft"] = array();
        foreach (Cart::GetInstans() as $row) {
            $this->view->cart["soft"][$row->id]["price"] = $row->getPriceWithCount();
            $this->view->cart["soft"][$row->id]["count"] = $row->count;
        }
        
        $this->view->payment = array();
        foreach (Order::$payment as $id => $payment) {
            $this->view->payment[$id] = $payment["name"];
        }
        
        $this->view->cart = Zend_Json_Encoder::encode($this->view->cart);
        $this->view->sale_js = Zend_Json_Encoder::encode($this->view->sale->getAll());
        $this->view->payment = Zend_Json_Encoder::encode($this->view->payment);
        $this->view->delivery = Zend_Json_Encoder::encode($this->view->delivery);
    }
    
    
    private function savePost() {
        $session = new Zend_Session_Namespace($this->session_name);
        if (!$session->__isset("post")) $session->post = array();
        $post = $this->getRequest()->getPost();
        foreach ($post as $key => $value) {
            $session->post[$key] = Controllers::GetTextCP1251($value);
        }
        if (isset($post["email"]) and !isset($post['subscribe'])) unset($session->post['subscribe']) ;
        //if (isset($post["key"]) and !isset($post["isset_key"])) unset($session->post["isset_key"]); 
        //if ($this->current_step == 3 and !isset($post["isset_key"])) unset($session->post["isset_key"]);
        if ($this->current_step == 3) {
            $cart = Cart::GetInstans();
            //if ($this->isKey() and isset($post['isset_key'])) {
            if (isset($post['key']) and $post['key'] != "") {
                $cart->setElectronicDelivery(true);
                //$cart->isElectronicDelivery = true;
                $cart->addKeyModuleAd("AD-".$post["key"]);
            }
            else {
                $cart->setElectronicDelivery(false);
                //$cart->isElectronicDelivery = false;
                $cart->addKeyModuleAd(null);
            }
            $cart->regenerate();
        }
    }
    
    
    /**
     * @return boolean
     */
    private function isKey() {
        $post = $this->getRequest()->getPost();
        if (!isset($post["key"])) return false;
        $pattern = "#[\\S]+\\d+#";
        if (preg_match($pattern, $post["key"])) return true;
        return false;
    }
    
    
    /**
     * Вернет массив POST данных сохраненных в сессии
     *
     * @return array
     */
    private function getPost() {
        $session = new Zend_Session_Namespace($this->session_name);
        if (!$session->__isset("post")) {
            $session->post = array();
            $user = UserInfo::GetInstans();
            if ($user) {
                $data = $user->getOrderData();
                if ($data) $session->post = $data;
            }
        }
        
        if (!isset($session->post["key"])) $session->post["key"] = $this->getKey();
        elseif ($session->post["key"] == "") $session->post["key"] = $this->getKey();
        //elseif ($session->post["key"] == "") $session->post["key"] = $this->getKey();
        return $session->post;
    }
    
    
    /**
     * @return string
     */
    private function getKey() {
        $cart = Cart::GetInstans();
        foreach ($cart as $item) {
            if (preg_match("#pr$#", $item->code_soft) and $item->key != "") return str_replace("AD-", "", $item->key);
        }
        return "";
    }
    
    
    /**
     * Replace " on '&#34' for correct view form
     *
     * @param array $post
     * @return array
     */
    private function clearPost($post) {
        foreach ($post as $key=>$value) {
            if (is_array($value) or $key == "delivery" or $key == "payment") continue;
            $post[$key] = $value = Scalar_String::Factory($value)->replace('"', "&#34;")->__toString();
        }
        return $post;
    }
    
    
    private function isValid() {
        
    }


    /**
     * @return array
     */
    private function getInfoNonUser() {
        $array_info = array();
        $post = $this->getPost();
        $array_info["Организация или ФИО"] = "";
        if (isset($post["org"]) and $post["org"] != "") $array_info["Организация или ФИО"] = $post["org"];
        if (isset($post["contact"]) and $post["contact"] != "") $array_info["Контактное лицо"] = $post["contact"];
    
        $array_info["Адрес доставки"] = "";
        if (isset($post["country"]) and $post["country"] != "") $array_info["Адрес доставки"] .= $post["country"]." ";
        if (isset($post["region"]) and $post["region"] != "") $array_info["Адрес доставки"] .= $post["region"]." ";
        if (isset($post["address"]) and $post["address"] != "") $array_info["Адрес доставки"] .= $post["address"];
    
        return $array_info;
    }
    
    
    /**
     * @return array
     */
    private function getInfoUser() {
        $array_info = array();
        $post = $this->getPost();
        $user = UserInfo::GetInstans();
        $array_info["Получатель"] = "";
        if (isset($post["org"])) {
            $post["org"] = (int) $post["org"];
            $table = new UserInfo_Table_Organization();
            $row = $table->fetchRow("user_id = $user->id and id = ".$post["org"]);
            if ($row) $array_info["Получатель"] = trim($row->name);
        }
        $array_info["Адрес"] = "";
        if (isset($post["address"])) {
            $post["address"] = (int) $post["address"];
            $table = new UserInfo_Table_Address();
            $row = $table->getFulAddress($post["address"]);
            if ($row) {
                $array_info["Адрес"] = "{$row["index"]} {$row["country_name"]} {$row["region_name"]} {$row["city_name"]} {$row["address"]}";
            }
        }
        return $array_info;
    }
    
    
    /**
     * @return array
     */
    private function getInfoContact() {
        $array_info = array();
        $post = $this->getPost();
        $array_info["Телефон"] = '';
        if (isset($post["phone"]) and $post["phone"] != "") $array_info["Телефон"] = $post["phone"];
        if (isset($post["fax"]) and $post["fax"] != "") $array_info["Факс"] = $post["fax"];
        if (isset($post["email"]) and $post["email"] != "") {
            $array_info["E-Mail"] = $post["email"];
            if (isset($post['subscribe'])) $array_info["E-Mail"] .= " (подписка на рассылку)";
        }
        if (isset($post["icq"]) and $post["icq"] != "") $array_info["ICQ"] = $post["icq"];
        if (isset($post["skype"]) and $post["skype"] != "") $array_info["Skype"] = $post["skype"];
        return $array_info;
    }
    

    private function getInfoPayment() {
        $post = $this->getPost();
        $array_info = array();
        if (isset($post["payment"]) and $post["payment"] != "") {
            $array_info["Оплата"] = Order::GetPaymentById($post["payment"]);
            switch ($post["payment"]) {
                case 1: {
                    if (isset($post["customerwmid"]) and $post["customerwmid"] != "") {
                        $array_info["Оплата"] .= ", WMID: ".$post["customerwmid"];
                    }
                    if (isset($post["storepurse"]) and $post["storepurse"] != "") {
                        $post["storepurse"] = strtolower($post["storepurse"]);
                        switch ($post["storepurse"]) {
                            case "rwm": $array_info["Оплата"] .= ", тип кошелька: Российсие рубли"; break;
                            case "zwm": $array_info["Оплата"] .= ", тип кошелька: Доллары США"; break;
                            case "ewm": $array_info["Оплата"] .= ", тип кошелька: Евро"; break;
                            case "gwm": $array_info["Оплата"] .= ", тип кошелька: Золото"; break;
                        }
                    }
                    break;
                }
                case 3: {
                    if (isset($post["bank"]) and $post["bank"] != "") $array_info["Оплата"] .= ", счет направлен на ".$post["bank"];
                    break;
                }
                case 5:{
                    if (isset($post["phone_for_qiwi"]) and $post["phone_for_qiwi"] != "") {
                        $array_info["Оплата"] .= ", номер мобильного телефона ".$post["phone_for_qiwi"];
                    }
                    break;
                }
            }
        }
        return $array_info;
    }
    
    
    /**
     * Save order
     */
    private function order() {
        $order_save = new Order_Save($this->getPost(), $this->view);
        $order_save->validateCaptcha = false;
        if (!$order_save->isValid()) return ;
        $order_save->save();
    
        $post = $this->getRequest()->getPost();
        if ($post["email"] != "" and isset($post["subscribe"])) Subscribe::SubscribeEmail($post["email"], true);
    
        $session = new Zend_Session_Namespace($this->session_name);
        $session->end = true;
        if (UserInfo::GetInstans()) {
            unset($session->post["code"]);
            unset($session->post["description"]);
            unset($session->post["isset_key"]);
            unset($session->post["key"]);
            UserInfo::GetInstans()->setOrderData($session->post);
        }
        $session->__unset("post");
        $session->__set("order_id", $order_save->order_id);
        $session->__set("errorQiwi", $order_save->errorQiwi);
        $session->__set("errorWM", $order_save->errorWM);
        $this->_redirect(Location::GetInstance()->get(-1)."/completed"); //work
    }
    
    
    public function addAction() {
        Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
        Zend_Controller_Action_HelperBroker::removeHelper('Layout');
        
        $json = array(
                "error" => 0,
                "message" => ""
        );
        
        if (!$this->getRequest()->isPost()) {
            $json["error"] = 2;
            $json["message"] = "Нет данных";
            echo Zend_Json_Encoder::encode($json);
            return;
        }
        $post = $this->getRequest()->getPost();
        
//      if (!isset($post["id"])) {
//          $json["error"] = 2;
//          $json["message"] = "Нет данных";
//          echo Zend_Json_Encoder::encode($json);
//          return;
//      }
        
//      if (!array($post["id"])) {
//          $json["error"] = 3;
//          $json["message"] = "Нет данных";
//          echo Zend_Json_Encoder::encode($json);
//          return;
//      }
        
        $cart = Cart::GetInstans();
        
        foreach ($post as $id => $key) {
            $id = (int) $id;
            $key = trim(htmlspecialchars(Controllers::GetTextCP1251($key)));
            $cart->deleteSoft($id);
            $cart->addSoft($id);
            if ($key != "") $cart->addKeys($id, $key);
        }
        $json["cart"] = $this->getCartData();
        echo Zend_Json_Encoder::encode($json);
    }
    
    
    public function deletebypageAction() {
        Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
        Zend_Controller_Action_HelperBroker::removeHelper('Layout');
        $json = array(
                "error" => 0,
                "message" => ""
        );
        if (!$this->getRequest()->isPost()) {
            $json["error"] = 2;
            $json["message"] = "Нет данных";
            echo Zend_Json_Encoder::encode($json);
            return;
        }
        $post = $this->getRequest()->getPost();
        if (!isset($post["id"])) {
            $json["error"] = 3;
            $json["message"] = "Нет данных";
            echo Zend_Json_Encoder::encode($json);
            return;
        }
        
        $cart = Cart::GetInstans()->deleteSoft($post["id"]);
        $json["cart"] = $this->getCartData();
        echo Zend_Json_Encoder::encode($json);
    }
    
    
    public function deleteAction() {
        Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
        Zend_Controller_Action_HelperBroker::removeHelper('Layout');
        if (!$this->_request->isPost()) return ;
        $post = $this->_request->getPost();
        if (!isset($post["id"])) return ;
        $json = array();
        $json["error"] = 0;
        $cart = Cart::GetInstans();
        $cart->deleteSoft($post["id"])->regenerate();
        //$json["count"] = $cart->getCountSoft(true);
        //$json["full_price"] = $cart->getFullPrice();
        $view = $this->view;
        if ($cart->getFullPrice() == 0) $json["html"] = $view->render("cart/empty-cart.tpl");
        else $json["html"] = $view->render("cart/step/step1.tpl");
        
        echo Zend_Json_Encoder::encode($json);
    }
    
    
    public function salepaymentAction() {
        Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
        Zend_Controller_Action_HelperBroker::removeHelper('Layout');
        $post = $this->getRequest()->getPost();
        $arr = array(
            "error" => 0,
            //"message" => "",
            "sale" => array(),
        );
        if (!isset($post["payment"])) {
            $arr["error"] = 1;
            echo Zend_Json_Encoder::encode($arr);
            return;
        }
        $payment = (int) $post["payment"];
        $code = "";
        $session = new Zend_Session_Namespace($this->session_name); 
        if ($session->__isset("post") and isset($session->post["code"])) $code = $session->post["code"];
        $sale = Sale::Factory($code, $payment);
        $arr["sale"] = $sale->getAll();
        $this->savePost();
        echo Zend_Json_Encoder::encode($arr);
    }
    
    
    public function completedAction() {
        $session = new Zend_Session_Namespace($this->session_name);
        if ($session->end) {
            $this->view->order_id = $session->order_id;
            $this->view->errorQiwi = $session->errorQiwi;
            $this->view->errorWM = $session->errorWM;
        }
        else $this->_redirect(Location::GetInstance()->get(-1));
        Zend_Session::namespaceUnset($this->session_name);
    }
    
    
    public function debugAction() {
        Zend_Controller_Action_HelperBroker::removeHelper('Layout');
        Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
        Cart::GetInstans()->debug();
    }
    
    
    public function oldAction() {
        if (Request::GetInstance()->getRemoteIp() != "87.224.202.126") throw new Zend_Controller_Action_Exception("Page not found", 404);
        d($_REQUEST);
        d($_POST);
        d($_GET);
    }
}
