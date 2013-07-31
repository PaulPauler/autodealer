<?php
class Order_Save {
  
	private $post;
	private $view;
	private $valid = null;
	/**
	 * @var UserInfo
	 */
	private $user = false;
	public $order_id;
	private $site_id = null;
	private $site_url;
	public $validateCaptcha = true;
	
	
	function __construct($post, $view) {
		$this->post = $this->encodingPostData($post);
		$this->view = $view;
		$this->user = UserInfo::GetInstans();
		//$this->valid = $this->_isValid();
	}
	
	
	/**
	 * @param array $data
	 * @return array
	 */
	private function encodingPostData($data) {
		foreach ($data as $key => $value) {
			if (is_array($value)) $data[$key] = $this->encodingPostData($value);
			else $data[$key] = Controllers::GetTextCP1251($value);
		}
		return $data;
	}
	
	
	/**
	 * Сохранение данных заказа
	 * @return bool
	 */
	function save() {
	
		if (Request::GetInstance()->getRemoteIp() == "85.12.249.104") {
			$adapter = Zend_Db_Table_Abstract::getDefaultAdapter();
			$post = $this->post;
			unset($post["org"]);
			unset($post["address"]);
			$adr = new UserInfo_Table_Address();
			$org = new UserInfo_Table_Organization();
			
			$addressId = (int) $post["address"];
			$orgId = $post["org"];
			$rowAdr = $adr->getFulAddress($addressId);
			$rowOrg = $org->fetchRow(array("id = $orgId", "user_id = {$this->user->id}"));
			if (!$rowAdr) {
				$adapter->rollBack();
				throw new Exception("Не найден id адреса = $addressId");
			}
			if (!$rowOrg) {
				$adapter->rollBack();
				throw new Exception("Не найдены данные id организации = $orgId, id адреса = $addressId"); 
			}
			
			try {
				//throw new Exception("Не найден id адреса");
				
				$this->homeSave();
			}
			catch (Exception $e) {
				throw new Zend_Exception($e->getMessage(), $e->getCode());
			}
			
			
			return;
			
		}
	
		if (!$this->valid) return ;
		$post = $this->post;
		if (Cart::GetInstans()->isElectronicDelivery and !Cart::GetInstans()->isPhysicalDelevery) $post["delivery"] = Order::ELECTRONIC_DELIVERY;
		$order = new Order();
		$bond = new Order_Soft();
		$adapter = Zend_Db_Table_Abstract::getDefaultAdapter();
		$adapter->beginTransaction();
		if ($this->isUser()) {
			$adr = new UserInfo_Table_Address();
			$org = new UserInfo_Table_Organization();
			
			$addressId = (int) $post["address"];
			$orgId = $post["org"];
			$rowAdr = $adr->getFulAddress($addressId);
			$rowOrg = $org->fetchRow(array("id = $orgId", "user_id = {$this->user->id}"));
			if (!$rowAdr) {
				$adapter->rollBack();
				throw new Exception("Не найден id адреса = $addressId");
			}
			if (!$rowOrg) {
				$adapter->rollBack();
				throw new Exception("Не найдены данные id организации = $orgId, id адреса = $addressId"); 
			}
		}
		
		try {
			$rowOrder = $order->createRow();
			$rowOrder->time_add = time();
			$rowOrder->bill_date = $rowOrder->time_add;
			$rowOrder->ip = Request::GetInstance()->getRemoteIp();
			$rowOrder->status = 0;
			if ($this->isUser()) {
				$rowOrder->user_id = $this->user->id;
				$rowOrder->address_id = $post["address"];
				$rowOrder->org_id = $post["org"];
				
				$rowOrder->org = $rowOrg->name;
				$rowOrder->country = $rowAdr["country_name"];
				$rowOrder->region = $rowAdr["region_name"];
				//$rowOrder->address = "{$rowAdr["country_name"]} {$rowAdr["region_name"]} {$rowAdr["index"]} {$rowAdr["city_name"]} {$rowAdr["address"]}";
				$rowOrder->address = "{$rowAdr["index"]} {$rowAdr["city_name"]} {$rowAdr["address"]}";
				if ($rowOrg->legal == 1) $rowOrder->legal_address = $rowOrg->address;
				$rowOrder->contact_face = $this->user->getFullName();
			}
			else {
				$rowOrder->country = htmlspecialchars($post["country"]);
				$rowOrder->region = htmlspecialchars($post["region"]);
				$rowOrder->org = htmlspecialchars($post["org"]);
				$rowOrder->address = htmlspecialchars($post["address"]);
				$rowOrder->contact_face = htmlspecialchars($post["contact"]);
			}
			// Место для партнеров
			if (SiteInfo::GetInstance()) $rowOrder->partner_id = SiteInfo::GetInstance()->id;
			elseif (Zend_Session::namespaceIsset("partner")) {
				$session = new Zend_Session_Namespace("partner");
				if ($session->__isset("id")) {
					$tUsers = new UserInfo_Table();
					$rUser = $tUsers->fetchRow("id=".$session->id);
					if ($rUser) {
						$rowOrder->partner_user = $rUser->id;
						Zend_Session::namespaceUnset("partner"); 
					}
				}
			}
			//********************************
			
			$rowOrder->delivery = (int) $post["delivery"];
			$payment = (int) $post["payment"];
			$rowOrder->payment = $payment;
			$comment = "";
			//if ($payment == Order::BANK) $comment = "Счет прислать: ".htmlspecialchars($post["bank"])."\r\n";
			if ($payment == Order::QIWI) $comment .= "Телефон пользователя QIWI: ".$post["phone_for_qiwi"]."\r\n";
			$rowOrder->phone = htmlspecialchars($post["phone"]);
			//$rowOrder->fax = htmlspecialchars($post["fax"]);
			$rowOrder->email = htmlspecialchars($post["email"]);
			$rowOrder->icq = htmlspecialchars($post["icq"]);
			$rowOrder->skype = htmlspecialchars($post["skype"]);
			
			$sale = Sale::Factory($post["code"], $rowOrder->payment);
			
			$comment .= htmlspecialchars($post["description"]);
			if ($sale->code != "") $rowOrder->description = "Код акции - $sale->code\nОписание: $sale->description\n";
			
			$rowOrder->comment = $comment;
			$rowOrder->hash = Order::GetHeshOrder(rand());
			$this->order_id = $id = $rowOrder->save();
			
			$rowOrder->num_order = "И".Scalar_String::Factory($id)->addSymbol("0", 4, Scalar_String::INSERT_BEFORE)->__toString();
			$rowOrder->save();
			
			$fullPrice = 0;
			foreach (Cart::GetInstans() as $soft) {
				$rowBond = $bond->createRow();
				$rowBond->order_id = $id;
				$rowBond->soft_id = $soft->id;
				$rowBond->count = $soft->count;
				$rowBond->key = $soft->key;
				$rowBond->price = $soft->getPriceWithoutDealer() * $soft->count;
// 				if ($this->user and $this->user->isDealer()) {
// 					$rowBond->percent = $this->user->percent_dealer;
// 					$fullPrice += $rowBond->price * (1 - $this->user->percent_dealer);
// 				}
// 				elseif ($sale->inSale($soft->id)) {
				if ($sale->inSale($soft->id)) {
					$rowBond->percent = $sale->getPercent($soft->id);
					$rowBond->discount = $sale->getDiscount($soft->id) * $soft->count;
					$fullPrice += $rowBond->price * (1 - $rowBond->percent) - $rowBond->discount;
				}
				else $fullPrice += $rowBond->price;
				$rowBond->save();
			}
			$fullPrice += (int) Order::GetDeliveryById($rowOrder->delivery, "price");
			$rowOrder->price = $fullPrice;
			$rowOrder->save();
			$adapter->commit();
		}
		catch (Exception $e) {
			$adapter->rollBack();
			throw new Zend_Exception($e->getMessage(), $e->getCode());
		}
		
		if ($post["payment"] == Order::QIWI) $this->sendQiwi($rowOrder);
		if ($post["payment"] == Order::WEBMONEY) $this->sendWebMoney($rowOrder);
		
		$this->sendMail($rowOrder);
		
		/* Запись в таблицу Order количество платежей системы QIWI  */
		if (QIWI::GetInstance()->multipart) {
			$rowOrder->count_qiwi_bill = QIWI::GetInstance()->getCountBill();
			$rowOrder->save();
		}
		
		Cart::GetInstans()->clear();
		$sale->decrease();
		
		//$adapter->commit();
		//return true;
	}
	
	
	private function homeSave() {
		ob_start();
		echo "POST DATA \n";
		var_dump($this->post);
		echo "_SERVER DATA \n";
		var_dump($_SERVER);
		$str = ob_get_clean();
		file_put_contents("/tmp/order".date("Ymd", time()), $str);
		$this->order_id = 9090;
	}
	
	
	/**
	 * @return bool
	 */
	private function isUser() {
		if ($this->user and ctype_digit($this->post["org"])) return true;
		return false;
	}
	
	
	private function sendMail($order) {
		$mailOrder = new Mail_Order($order, Cart::GetInstans()->isRequisiteSecond);
		$mailOrder->send();
		if ($order->email != "") {
			$mailUser = new Mail_User($order, Cart::GetInstans()->isRequisiteSecond);
			$mailUser->send();
		}
		$site = SiteInfo::GetInstance();
		if ($site) {
			if ($site->email != "" or $site->user_email != "") {
				$mailPartner = new Mail_Partner($order);
				$mailPartner->send();
			}
		}
	}
	
	public $errorQiwi = false;
	
	private function sendQiwi($order) {
		$qiwi = QIWI::Factory();
		$qiwi->setUser($this->post["phone_for_qiwi"]);
		$qiwi->setAmount(Order::GetPrice($order->id));
		$qiwi->txn = $order->id;
		$qiwi->comment = 'ООО "Компания" АвтоДилер" - Заказ №'.$order->id;		
		$qiwi->createBill();
		$this->errorQiwi = $qiwi->error;
		return ;
		
		// Сократили комент т.к. QIWI выдавал ошибку при слишком длинном коменте
		// Ниже код, который формирует комент
		
		$qiwi->comment = 'ООО "Компания" АвтоДилер" - Заказ №'.$order->id."\r\nПродукты (сумма ".$qiwi->getAmount()."р.):\r\n";
		$sale = Sale::GetInstance();
		foreach (Cart::GetInstans() as $row) {
			$full_price = $row->getPriceWithCount();
			if ($sale->inSale($row->id)) {
				$full_price = $full_price * (1 - $sale->getPercent($row->id)) - $sale->getDiscount($row->id);
			}
			$qiwi->comment .= $row->label_blank." ".$row->count."шт. - $full_price р.\r\n";
		}
		$qiwi->comment .= "Доставка: ".Order::GetDeliveryById($order->delivery, "name");
		if ($order->delivery == Order::POCHTA or $order->delivery == Order::COURIER) $qiwi->comment .= " - ".Order::GetDeliveryById($order->delivery, "price");
		
		$qiwi->createBill();
		$this->errorQiwi = $qiwi->error;
	}
	
	
	public $errorWM = false;
	
	private function sendWebMoney($order) {
		$wm = new WebMoney();
		//$wm->amount = $this->getCurrencyRate(Order::GetPrice($order->id));
		$wm->setAmount($this->getCurrencyRate(Order::GetPrice($order->id)));
		$wm->order_id = $order->id;
		$wm->customerwmid = $this->post["customerwmid"];
		$wm->storepurse = WebMoney::$Currency[$this->post["storepurse"]]["purse"];
		$wm->description = 'ООО "Компания" АвтоДилер" - Заказ №'.$order->id." на сумму ".$wm->getAmount();
		$wm->address = $order->country." ".$order->region." ".$order->address;
		if (!$wm->createBill()) $this->errorWM = true;
	}
	
	
	
	private function getCurrencyRate($amount) {
		try {
			$table = new Info();
			$row = $table->fetchRow();
			$storepurse = $this->post["storepurse"];
			$currencyRate = $row->$storepurse;
		}
		catch (Exception $e) {
			$currencyRate = 1;
		}
		return round($amount / $currencyRate, 2);
	}
	
	
	/**
	 * @return bool
	 */
	function isValid() { 
		if (is_null($this->valid)) $this->valid = $this->_isValid();
		return $this->valid;
	}
	
	 
	private function _isValid() {
		$post = $this->post;
		$result = true;
		if (isset($post["delivery"])) {
			$delivery = (int) $post["delivery"];
			if ($delivery < 1 or $delivery > 3) {
				$result = false;
				$this->view->messageDelivery = "Вы не указали способ доставки";
			}
		}
		elseif (!Cart::GetInstans()->isElectronicDelivery) {
			$result = false;
			$this->view->messageDelivery = "Вы не указали способ доставки";
		}
//		elseif (Cart::GetInstans()->isElectronicDelivery and $post["email"] == "") {
//			$result = false;
//			$this->view->messageEMail = "Вы не указали E-Mail";
//		}

		if (isset($post["email"]) and trim($post["email"]) != "") {
			$email = trim($post["email"]);
			if (!Controllers::CheckMail($email)) {
				$this->view->messageEmail = "Не верно указан E-Mail";
				$valid = false;
			}
		}
		else {
			$this->view->messageEmail = "Не указан E-Mail";
			$valid = false;
		}
		
		if (isset($post["payment"])) {
			$payment = (int) $post["payment"];
			if ($payment < 1 or $payment > 5) {
				$result = false;
				$this->view->messagePayment = "Вы не указали способ оплаты";
			}
//			if ($payment == 3 and !isset($post["bank"])) {
//				$result = false;
//				$this->view->messagePayment = "Вы не указали как направить счет";
//			}
//			elseif ($post["bank"] == "E-Mail" and $post["email"] == "" and $post["payment"] == 3) {
//				$result = false;
//				$this->view->messageEMail = "Вы не указали E-Mail";
//			}
//			elseif ($post["bank"] == "факс" and $post["fax"] == ""  and $post["payment"] == 3) {
//				$result = false;
//				$this->view->messageFax = "Вы не указали факс";
//			}
			
			if ($payment == Order::WEBMONEY) {
				if ($post["customerwmid"] == "" or ($post["customerwmid"] != "" and !ctype_digit($post["customerwmid"]))) {
					$result = false;
					$this->view->messagePayment = "Не указан WMID.";
				}
				if (!isset($post["storepurse"])) {
					$result = false;
					$this->view->messagePayment .= " Не выбран кошелек.";
				}
			}
			
			if ($payment == Order::QIWI and !QIWI::IsValidPhone($post["phone_for_qiwi"])) {
				$result = false;
				$this->view->messagePayment = "Не верно указан номер телефона";
			}
		}
		else {
			$result = false;
			$this->view->messagePayment = "Вы не указали способ оплаты";
		}
		
		
		if ($this->isUser()) {
			if (!$this->isValidAuth()) $result = false;
		}
		else {
			if (!$this->isValidAnon()) $result = false;
		}
		
		if (!isset($post["phone"]) or $post["phone"] == "") {
			$result = false;
			$this->view->messagePhone = "Вы не указали телефон";
		}

		if (!Controllers::CheckMail($post["email"])) {
			$result = false;
			$this->view->messageEMail = "Неверно указан E-Mail";
		}
		
		return $result;
	}
	
	
	private function isValidAuth() {
		$result = true;
		if (!isset($this->post["org"])) {
			$result = false;
			$this->view->message_org = "Вы не указали плательщика";
		}
		
		if (!isset($this->post["address"])) {
			$result = false;
			$this->view->message_address = "Вы не указали адрес доставки";
		}
		return $result;
	}
	
	
	private function isValidAnon() {
		$result = true;
		
		if (!isset($this->post["region"]) or $this->post["region"] == "") {
			$result = false;
			$this->view->messageRegion = "Не указан регион";
		}
		
		if (!isset($this->post["address"]) or $this->post["address"] == "") {
			$result = false;
			$this->view->messageAddress = "Не указан адрес доставки";
		}
		
		if (!isset($this->post["org"]) or $this->post["org"] == "") {
			$result = false;
			$this->view->messageOrg = "Не указан плательщик";
		}
		
		if ($this->validateCaptcha and !Capcha::IsValid($this->post['capcha'])) {
			$this->view->messageCapcha = "Неправильно введен код";
			$result = false;
		}
		return $result;
	}
}
