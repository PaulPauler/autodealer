<?
class Shop_UpdateController extends Shop_Controllers {
    
    public function indexAction() {
        $this->view->soft_ids = $this->getSoftIds(Cart::GetInstans());
        
        $page = $this->getPage();
        $this->buildPath($page);
        
//      Path::GetInstance()->add("������� �� ����� ������");
//      $this->view->title = "�������� ���������� �� ������ ������ �������� ������������ � ����������� �� ������� ����������";
//      $this->view->description = "�������� ���������� �� ������ ������ �������� ������������ � ����������� �� ������� ����������";
//      $this->view->keywords = "������ ������� ����������, �����������, ����������, ���������, ����������������, �����������";
    }
}
