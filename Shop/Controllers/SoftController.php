<?
class Shop_SoftController extends Shop_Controllers {
    
//  public function init() {
//      parent::init();
//      Path::GetInstance()->add("Программное обеспечение");
//  }
    
    
    public function indexAction() {
        $this->_redirect(Location::GetInstance()->get(-1));
        //$this->view->soft_ids = $this->getSoftIds(Cart::GetInstans());
        //d(Cart::GetInstans()->getCountSoft());
        //Cart::GetInstans()->debug();
    }
}
