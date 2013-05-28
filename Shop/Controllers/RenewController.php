<?
class Shop_RenewController extends Shop_Controllers {
    
    public function indexAction() {
        $page = $this->getPage();
        $this->buildPath($page);
        $key = "";
        if (isset($_GET["key"])) $key = htmlspecialchars($_GET["key"]);
        $this->view->content = str_ireplace("<!--KEY-->", $key, $this->view->content);
        
        
//      $this->view->key = "";
//      if (isset($_GET["key"])) $this->view->key = htmlspecialchars($_GET["key"]);
//      $this->view->soft_ids = $this->getSoftIds(Cart::GetInstans());
//      Path::GetInstance()->add("Продление лицензии");
//      $this->view->title = "Заказать продление лицензии на модули программы «АвтоДилер» - АвтоМагазин, АвтоСервис, АвтоНормы, АвтоКаталог, АвтоПланирование, АвтоСтрахование, Техосмотр";
//      $this->view->description = "Продление лицензии на программы компании «АвтоДилер»";
//      $this->view->keywords = "Продление лицензии, автодилер, модули системы «автодилер», автомагазин, автосервис, автонормы, автопланирование, автокаталог, автострахование, техосмотр, диски с базами данных";
    }
}
