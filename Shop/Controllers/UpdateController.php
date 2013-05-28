<?
class Shop_UpdateController extends Shop_Controllers {
    
    public function indexAction() {
        $this->view->soft_ids = $this->getSoftIds(Cart::GetInstans());
        
        $page = $this->getPage();
        $this->buildPath($page);
        
//      Path::GetInstance()->add("Переход на новую версию");
//      $this->view->title = "Заказать обновление со старых версий программ «АвтоКаталог» и «АвтоСервис» до системы «АвтоДилер»";
//      $this->view->description = "Заказать обновление со старых версий программ «АвтоКаталог» и «АвтоСервис» до системы «АвтоДилер»";
//      $this->view->keywords = "модули системы «автодилер», автомагазин, автосервис, автонормы, автопланирование, автокаталог";
    }
}
