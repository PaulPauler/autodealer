<?
class Shop_ActionController extends Shop_Controllers {
    
    public function init() {
        Path::GetInstance()->add("Интернет-магазин");
    }
    
    
    public function indexAction() {
        Path::GetInstance()->add("Акция Trade In");
        $this->view->title = "Акция «Trade in»: обмен программ других производителей на «АвтоДилер» со скидкой 40%";
        $this->view->description = "Акция «Trade in»: обмен программ других производителей на «АвтоДилер» со скидкой 40%";
        $this->view->keywords = "trade in, автодилер";
    }
    
    
    public function emoneyAction() {
        $time_start_banner = mktime(16, 0, 0, 12, 29, 2012);
        $time_start = mktime(0, 0, 0, 12, 30, 2012);
        $time_end = mktime(0, 0, 0, 1, 9, 2013);
        $time = time();
        if ($time < $time_start_banner or $time > $time_end) throw new Zend_Controller_Action_Exception("Page not found", 404);
        Path::GetInstance()->add("Акция: только в новогодние праздники скидка 50%");
        $this->view->title = "Акция: только в новогодние праздники скидка 50%";
        $this->view->description = "Акция: только в новогодние праздники скидка 50%";
        $this->view->keywords = "акция, автодилер, 50%, новогодние праздники, скидка";
    }
}
