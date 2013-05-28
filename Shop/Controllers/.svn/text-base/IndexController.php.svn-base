<?
class Shop_IndexController extends Shop_Controllers {
    
    public function indexAction() {
        //Path::GetInstance()->add("Программное обеспечение");
        $this->view->soft_ids = $this->getSoftIds(Cart::GetInstans());
        
        $page = $this->getPage();
        $this->buildPath($page);
        
//      $this->view->title = "Программы для автобизнеса";
//      $this->view->description = "Самый простой способ приобрести программы для автобизнеса компании «АвтоДилер»";
//      $this->view->keywords = "спецпредложения, модули системы «автодилер», автомагазин, автосервис, автонормы, автопланирование, автокаталог, автострахование, техосмотр, диски с базами данных";
    }
}
