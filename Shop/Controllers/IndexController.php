<?
class Shop_IndexController extends Shop_Controllers {
    
    public function indexAction() {
        //Path::GetInstance()->add("����������� �����������");
        $this->view->soft_ids = $this->getSoftIds(Cart::GetInstans());
        
        $page = $this->getPage();
        $this->buildPath($page);
        
//      $this->view->title = "��������� ��� �����������";
//      $this->view->description = "����� ������� ������ ���������� ��������� ��� ����������� �������� ����������";
//      $this->view->keywords = "���������������, ������ ������� ����������, �����������, ����������, ���������, ����������������, �����������, ���������������, ���������, ����� � ������ ������";
    }
}
