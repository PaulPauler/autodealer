<?
class Shop_Controllers extends Controllers {
    
    /**
     * @var Pages
     */
    protected $page_table;
    
    
    public function preDispatch() {
        parent::preDispatch();
        $this->view->headScript()->appendFile("js/cart.js?1");
        $this->view->headScript()->appendFile("js/inputDefault.js?1");
    }
    
    public function init() {
        //Path::GetInstance()->add("Интернет-магазин");
    }
    
    
    protected function getCartData() {
        $cart = Cart::GetInstans();
        $cart->regenerate();
        $arr = array();
        $arr["full_price"] = (string) $cart->getFullPrice();
        $arr["count_soft"] = $cart->getCountSoft(true);
        if ($arr["full_price"] > 0) $arr["img"] = "images/design/icon/64/shopping_basket_full.png";
        else $arr["img"] = "images/design/icon/64/shopping_basket.png";
        return $arr;
    }
    
    
    protected function getSoftIds($rows) {
        $result = array();
        foreach ($rows as $row) {
            $result[] = $row->getBoxId();
            if (!empty($row->children)) $result = array_merge($result, $this->getSoftIds($row->children));
        }
        return $result;
    }
    
    
    /**
     * @param Zend_Db_Table_Row $page
     */
    protected function buildPath($page) {
        $uri = strtolower($page->uri);
        $page_names = array();
        $row = clone $page;
    
        while ($row->parent_id != 0) {
            $select = $this->page_table->select()->where('id = ?', $row->parent_id);
            $row = $this->page_table->fetchRow($select);
            $page_names[] = $row->page_name;
            $uri = $row->uri;
        }
    
        $path = Path::GetInstance();
        for ($i = count($page_names)-1; $i >= 0; $i--) {
            $path->add($page_names[$i]);
        }
        $path->add($page->page_name);
    }
    
    
    /**
     * @param bool $check
     * @param bool $fillHead
     * @throws Zend_Controller_Action_Exception
     * @return Zend_Db_Table_Row_Abstract or NULL
     */
    protected function getPage($check = true, $fillHead = true) {
        $this->page_table = new Pages();
        $select = $this->page_table->select();
        $select->where("uri=?", Location::GetInstance()->__toString());
        $row = $this->page_table->fetchRow($select);
        if ($check and !$row) throw new Zend_Controller_Action_Exception("Page not found", 404);
        if ($row->redirect_to != "") $this->_redirect($row->redirect_to);
        if ($fillHead) {
            $this->view->title = $row->page_title;
            $this->view->keywords = $row->keywords_tag;
            $this->view->description = $row->description;
            $this->view->content = $row->content;
        }
        return $row;
    }
}
