<?
class Shop_SpecialController extends Shop_Controllers {
    
    public function indexAction() {
        $this->view->soft_ids = $this->getSoftIds(Cart::GetInstans());
        $page = $this->getPage();
        $this->buildPath($page);
//      Path::GetInstance()->add("—пецпредложени€");
//      $this->view->title = "—пециальные предложени€ компании Ђјвтоƒилерї дл€ автосервисов, автомагазинов, шиномонтажных мастерских, автомоек, станций замены масла";
//      $this->view->description = "—пециальные предложени€ компании Ђјвтоƒилерї дл€ автосервисов, автомагазинов, шиномонтажных мастерских, автомоек, станций замены масла";
//      $this->view->keywords = "спецпредложени€, модули системы Ђавтодилерї, автомагазин, автосервис, автонормы, автопланирование, автокаталог, автострахование, техосмотр, диски с базами данных";
    }
}
