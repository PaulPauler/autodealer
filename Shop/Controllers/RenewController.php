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
//      Path::GetInstance()->add("��������� ��������");
//      $this->view->title = "�������� ��������� �������� �� ������ ��������� ���������� - �����������, ����������, ���������, �����������, ����������������, ���������������, ���������";
//      $this->view->description = "��������� �������� �� ��������� �������� ����������";
//      $this->view->keywords = "��������� ��������, ���������, ������ ������� ����������, �����������, ����������, ���������, ����������������, �����������, ���������������, ���������, ����� � ������ ������";
    }
}
