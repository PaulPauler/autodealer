<?
class Shop_ActionController extends Shop_Controllers {
    
    public function init() {
        Path::GetInstance()->add("��������-�������");
    }
    
    
    public function indexAction() {
        Path::GetInstance()->add("����� Trade In");
        $this->view->title = "����� �Trade in�: ����� �������� ������ �������������� �� ���������� �� ������� 40%";
        $this->view->description = "����� �Trade in�: ����� �������� ������ �������������� �� ���������� �� ������� 40%";
        $this->view->keywords = "trade in, ���������";
    }
    
    
    public function emoneyAction() {
        $time_start_banner = mktime(16, 0, 0, 12, 29, 2012);
        $time_start = mktime(0, 0, 0, 12, 30, 2012);
        $time_end = mktime(0, 0, 0, 1, 9, 2013);
        $time = time();
        if ($time < $time_start_banner or $time > $time_end) throw new Zend_Controller_Action_Exception("Page not found", 404);
        Path::GetInstance()->add("�����: ������ � ���������� ��������� ������ 50%");
        $this->view->title = "�����: ������ � ���������� ��������� ������ 50%";
        $this->view->description = "�����: ������ � ���������� ��������� ������ 50%";
        $this->view->keywords = "�����, ���������, 50%, ���������� ���������, ������";
    }
}
