<?
define('DS', DIRECTORY_SEPARATOR);
class Init {
	
	static private $obj = null;
	
	const TIME = 1209600; // 60*60*24*14
	const SESSION_NAME = "sid";
	
	private $time;
	
	/**
	 * @return Init
	 */
	static function GetInstans(){
		if (is_null(self::$obj)) self::$obj = new self();
		return self::$obj;
	}
	
	//static private $domain;
	
	
	function __construct() {
		$this->time = microtime(true);
		function d($mixed) {
			echo "<pre>\n";
			var_dump($mixed);
			echo "</pre>";
		}
		chdir(dirname(__FILE__).'/..');
		$this->setIncludes();
		$this->classLoader();
		$this->setLocale();
	}
	
	
	private function setLocale() {
		setlocale(LC_ALL, 'ru_RU.UTF-8');
		if (PHP_OS == 'WINNT') setlocale(LC_ALL, 'ru_RU.cp1251');
	}
	
	
	private function setIncludes() {
		$ds = DS;
		$incPath = get_include_path().PATH_SEPARATOR.getcwd().DS."Modules{$ds}Partner{$ds}Models";
		$incPath .= PATH_SEPARATOR.getcwd().DS."Models";
		set_include_path($incPath);
		//echo get_include_path();exit;
	}
	
	
	private function classLoader() {
		include('ClassLoader.php');
		classLoader::Init();
		//include ('lib/Zend/Loader.php');
		//Zend_Loader::registerAutoload();
	}
	
	
	function startStatistics() {
		if (Configure::GetInstans("system")->stat) {
			$stat = SiteStatistics::Run();
			$stat->setStartTime($this->time);
		}
	}
	
	
	function startFireWall() {
	/*
		$request = array(
			'REQUEST' => $_REQUEST,
			'GET' => $_GET,
			'POST' => $_POST,
			'COOKIE' => $_COOKIE
		);
		$init = IDS_Init::init('/www/Modules/IDS/Config/Config.ini');
		$ids = new IDS_Monitor($request, $init);
		$result = $ids->run();
		if (!$result->isEmpty()) {
			// Take a look at the result object
			//echo $result;
			$compositeLog = new IDS_Log_Composite();
			$compositeLog->addLogger(IDS_Log_File::getInstance($init));
			$compositeLog->execute($result);
		}
	*/
	}
	
	
	function addInludePath($path) {
		$incPath = get_include_path().PATH_SEPARATOR.$path;
		set_include_path($incPath);
	}
	
	
	function configure() {
		Configure::GetInstans();
	}
	
	
	function setExceptionHandler() {
		set_error_handler(array($this, "onError"), E_ALL);
		//set_exception_handler();
	}
	
	
	function onError($code, $message, $file, $line, $context) {
		include 'system/PHPException.php';
		throw new PHPException($message, $code, $file, $line, $context);
	}
	
	
	/**
	 * @var Zend_Controller_Router_Rewrite
	 */
	private $route;
	function installRoute() {
		$this->route = $route = new Zend_Controller_Router_Rewrite();

		if (Configure::GetInstans("system")->reconstruction and Request::GetInstance()->getRemoteIp() != "87.224.171.15") {
			$route->addRoute('reconstruction',
					new Zend_Controller_Router_Route('*', array('module' => 'default', 'controller' => 'reconstruction', 'action' => 'index')));
			return;
		}
		
		if (Configure::GetInstans("route")) $route->addConfig(Configure::GetInstans(), 'router');

		$route->addRoute('JavaScript',
			new Zend_Controller_Router_Route_Regex('js/lib.*', array('module' => 'default', 'controller' => 'JavaScript', 'action' => 'index')));
			
		$route->addRoute('Partner_JavaScript',
			new Zend_Controller_Router_Route('partner.js/*', array('module' => 'Partner', 'controller' => 'JavaScript', 'action' => 'index')));

		$this->installRouteOnLine($route);
		$this->installRouteAutoprice($route);
		$this->installRouteDealer($route);
			
		
//		$route->addRoute('cabinet/dealer',
//			new Zend_Controller_Router_Route('cabinet/dealer/*', array('module' => 'Dealer')));
		
		$route->addRoute('Partner_js',
			new Zend_Controller_Router_Route('partner/js/*', array('module' => 'Partner', 'controller' => 'JavaScriptContent', 'action' => 'index')));
			
		$route->addRoute('GetTimeDirect',
			new Zend_Controller_Router_Route('gettimedirect/*', array('module' => 'default', 'controller' => 'GetTimeDirect', 'action' => 'index')));
			
		$route->addRoute('GetCatalogDirect',
			new Zend_Controller_Router_Route('getcatalogdirect/*', array('module' => 'default', 'controller' => 'Getcatalogdirect', 'action' => 'index')));
			
		// Времмено для раздела soft
		$route->addRoute('soft/other',
				new Zend_Controller_Router_Route_Regex('soft/(?!rtimes|acat|feedback).*',
						array('module' => 'Soft', 'controller' => 'Index', 'action' => 'index')));
	}
	
	
	/**
	 * @param Zend_Controller_Router_Rewrite $route
	 */
	private function installRouteAutoprice($route) {
		$route->addRoute('autoprice/firm',
			new Zend_Controller_Router_Route_Regex('autoprice/firm/(\d+)', 
				array('module' => 'Autoprice', 'controller' => 'Firm', 'action' => 'view', 'firmId' => 0), array(1 => 'firmId')));
				
		$route->addRoute('autoprice/price',
			new Zend_Controller_Router_Route('autoprice/price/:priceId', 
				array('module' => 'Autoprice', 'controller' => 'Price', 'action' => 'index', 'priceId' => 0), array("priceId" => '\d+')));
	}
	
	
	/**
	 * @param Zend_Controller_Router_Rewrite $route
	 */
	private function installRouteOnLine($route) {
		$route->addRoute('online',
			new Zend_Controller_Router_Route('online', 
				array('module' => 'Online', 'controller' => 'All', 'action' => 'all')));
				
		$route->addRoute('online/pay',
			new Zend_Controller_Router_Route('online/pay/*', 
				array('module' => 'Online', 'controller' => 'All', 'action' => 'all')));
				
		$route->addRoute('online/finesgibdd',
			new Zend_Controller_Router_Route('online/finesgibdd/*', 
				array('module' => 'Online', 'controller' => 'All', 'action' => 'all')));
				
		$route->addRoute('online/pdd',
			new Zend_Controller_Router_Route('online/pdd/*', 
				array('module' => 'Online', 'controller' => 'All', 'action' => 'all')));
				
		$route->addRoute('online/pdd/exam/biletId',
			new Zend_Controller_Router_Route_Regex('online/pdd/exam/(\d+)', 
				array('module' => 'Online', 'controller' => 'Pdd', 'action' => 'exam', 'biletId' => 0), array(1 => "biletId")));
				
		$route->addRoute('online/osago',
			new Zend_Controller_Router_Route('online/osago/*', 
				array('module' => 'Online', 'controller' => 'All', 'action' => 'all')));
				
		$route->addRoute('online/osago/calc/print',
			new Zend_Controller_Router_Route('online/osago/calc/print', 
				array('module' => 'Online', 'controller' => 'Osago', 'action' => 'calcprint')));
				
		$route->addRoute('online/osago/calc',
			new Zend_Controller_Router_Route('online/osago/calc', 
				array('module' => 'Online', 'controller' => 'Osago', 'action' => 'calc')));
				
		$route->addRoute('online/putlist',
			new Zend_Controller_Router_Route('online/putlist/*', 
				array('module' => 'Online', 'controller' => 'All', 'action' => 'all')));
				
		$route->addRoute('online/putlist/form3/print',
			new Zend_Controller_Router_Route('online/putlist/form3/print', 
				array('module' => 'Online', 'controller' => 'Putlist', 'action' => 'printform3')));
				
		$route->addRoute('online/putlist/form4/print',
			new Zend_Controller_Router_Route('online/putlist/form4/print', 
				array('module' => 'Online', 'controller' => 'Putlist', 'action' => 'printform4')));
				
		$route->addRoute('online/distance',
			new Zend_Controller_Router_Route('online/distance/*', 
				array('module' => 'Online', 'controller' => 'All', 'action' => 'All')));
		
		$route->addRoute('top/view',
			new Zend_Controller_Router_Route('online/top/:siteId', 
				array('module' => 'Online', 'controller' => 'Top', 'action' => 'view'), array("siteId" => '\d+')));
				
		$route->addRoute('top/jump',
			new Zend_Controller_Router_Route('online/top/jump/:siteId', 
				array('module' => 'Online', 'controller' => 'Top', 'action' => 'jump', 'siteId' => 0), array("siteId" => '\d+')));
				
		$route->addRoute('online/detail/detail',
			new Zend_Controller_Router_Route('online/detail/detail/:detailId', 
				array('module' => 'Online', 'controller' => 'Detail', 'action' => 'detail', 'detailId' => 0), array("detailId" => '\d+')));
				
		$route->addRoute('online/detail/detail/model',
			new Zend_Controller_Router_Route_Regex('online/detail/detail/(\d+)/(\d+)', 
				array('module' => 'Online', 'controller' => 'Detail', 'action' => 'model', 'detailId' => 0, 'markId' => 0), array(1 => 'detailId', 2 => 'markId')));
	}
	
	
	/**
	 * @param Zend_Controller_Router_Rewrite $route
	 */
	private function installRouteDealer($route) {		
		$route->addRoute('dealer/card',
			new Zend_Controller_Router_Route_Regex('dealer/card/?(\d+)?/?(\d+)?', 
				array('module' => 'Dealer', 'controller' => 'Card', 'action' => 'index', 'keyId' => 0, 'cardId' => 0), array(1 => 'keyId', 2 => 'cardId')));
				
		$route->addRoute('dealerEdit',
			new Zend_Controller_Router_Route_Regex('dealer/info/(\d+)', 
				array('module' => 'Dealer', 'controller' => 'Info', 'action' => 'edit', 'dealerId' => 0), array(1 => 'dealerId')));
				
		$route->addRoute('dealer/invoice/show',
			new Zend_Controller_Router_Route_Regex('dealer/invoice/(\d+)', 
				array('module' => 'Dealer', 'controller' => 'Invoice', 'action' => 'show', 'invoiceId' => 0), array(1 => 'invoiceId')));
				
		$route->addRoute('dealer/export/excel',
			new Zend_Controller_Router_Route_Regex('dealer/export/excel/(\d+)/[\s\S]+', 
				array('module' => 'Dealer', 'controller' => 'Export', 'action' => 'excel', 'cardId' => 0), array(1 => 'cardId')));
		
//		$route->addRoute("Dealer", new Zend_Controller_Router_Route('cabinet/dealer', 
//				array('module' => 'Dealer')));
		
//		$route->addRoute('cabinet/dealer',
//			new Zend_Controller_Router_Route_Regex('cabinet/dealer/', array('module' => 'Dealer')));
		
			
	}
	
	
	function setLayout() {
		$config = Configure::GetInstans('layout');
		$configPath = Configure::GetInstans('path');
		$baseDir = getcwd().Request::GetInstance()->getBaseDir();
		Zend_Layout::startMvc(array(
			'layoutPath' => realpath($baseDir.$configPath->layoutPath),
            'layout' => $config->layout,
		));
		
		$layout = Zend_Layout::getMvcInstance();
		$layout->setViewSuffix($config->layoutSuffix);
		//$layout->setLayout("index");
		
		$view = $layout->getView(); /*@var $view Zend_View_Interface */
		$view->baseUrl = "/";
		$view->setEncoding("cp1251");
		$view->setBasePath($baseDir.$configPath->viewPath);
		$view->setScriptPath($baseDir.$configPath->viewPath);
		$layout->setView($view);
		
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
		$viewRenderer->setView($view)->setViewSuffix($config->viewSuffix);

		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
	}
	
	
	function setDb() {
		$config = Configure::GetInstans();
		if (preg_match("/v2/", Location::GetInstance()->base)) $db = Zend_Db::factory('Pdo_Mysql', $config->database_v2);
		else $db = Zend_Db::factory('Pdo_Mysql', $config->database);
		//$db->query("SET NAMES cp1251");
		Zend_Db_Table_Abstract::setDefaultAdapter($db);
	}
	
	
	function setSession() {
// 		$path = getcwd().DIRECTORY_SEPARATOR."var".DIRECTORY_SEPARATOR."session";
// 		//$path = "tcp://127.0.0.1:11211?persistent=1&weight=1&timeout=1&retry_interval=15";
// 		Zend_Session::start(array(
// 			'save_handler' => 'files',
// 			//'cookie_domain' => self::GetDomain(),
// 			'name' => self::SESSION_NAME,
// 			'gc_maxlifetime' => self::TIME,
// 			'cookie_lifetime' => 0,
// 			'save_path' => $path,
// 		));
		
		
		Zend_Session::start(array(
		//'save_handler' => 'files',
		'cookie_domain' => self::GetDomain(),
		'name' => self::SESSION_NAME,
		'gc_maxlifetime' => self::TIME,
		'cookie_lifetime' => 0,
		//'save_path' => $path,
		));
		
		return ;
	}
	
	
	function start() {
		$front = Zend_Controller_Front::getInstance();
		$front->throwexceptions(Configure::GetInstans("debug")->debug);
		$front->setRouter($this->route);
		$front->setControllerDirectory(array(
			'default' => 'Controllers',
			'Cabinet' => 'Modules/Cabinet/Controllers',
			'cabinet' => 'Modules/Cabinet/Controllers',
			'partner' => 'Modules/Partner/Controllers',
			'Partner' => 'Modules/Partner/Controllers',
			'ajax' => 'Modules/Ajax/Controllers',
			'Ajax' => 'Modules/Ajax/Controllers',
			'autoprice' => 'Modules/Autoprice/Controllers',
			'Autoprice' => 'Modules/Autoprice/Controllers',
			'online' => 'Modules/Online/Controllers',
			'Online' => 'Modules/Online/Controllers',
			'dealer' => 'Modules/Dealer/Controllers',
			'Dealer' => 'Modules/Dealer/Controllers',
			'Soft' => 'Modules/Soft/Controllers',
			'soft' => 'Modules/Soft/Controllers',
			'Shop' => 'Modules/Shop/Controllers',
			'shop' => 'Modules/Shop/Controllers',
			'Program_autodealer' => 'Modules/ProgramAutodealer/Controllers',
			'program_autodealer' => 'Modules/ProgramAutodealer/Controllers',
			'Download' => 'Modules/Download/Controllers',
			'download' => 'Modules/Download/Controllers',
			'Autoportal' => 'Modules/Autoportal/Controllers',
			'autoportal' => 'Modules/Autoportal/Controllers',
		));
//		$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
//		$redirector->setExit(false);
		//$front->setBaseUrl(Request::GetInstance()->getBaseDir());
		
		$front->dispatch();
		//d(self::getWWWDir());exit;
		
		$stat = SiteStatistics::GetInstance();
		if ($this->route->getCurrentRouteName() != "JavaScript" && Configure::GetInstans("system")->stat && !$stat->isBot()) {
			$profile = Zend_Db_Table::getDefaultAdapter()->getProfiler();
			if ($profile->getQueryProfiles()) {
				foreach ($profile->getQueryProfiles() as $query) {
					$stat->setSql($query->getQuery(), $query->getElapsedSecs());		
				}
			}
			$stat->setHtmlCode($front->getResponse()->getHttpResponseCode());
			$stat->save();
		}
	}
	
	
	static function CookieLifeTime($time) {
		if ($time > 0) $time = time() + $time;
		else $time = null;
		setcookie(self::SESSION_NAME, Zend_Session::getId(), $time, "/", self::GetDomain(), false, false);
	}
	
	
	static function GetDomain() {
		$host = Request::GetInstance()->getHost();
		$matches = array();
		preg_match("#\\w*\\.\\w*$#", $host, $matches);
		return ".".$matches[0];
	}
	
	
	/**
	 * Возращает приватный ключ для дилеров
	 * @return string
	 */
	static function GetDealerPrivateKey() {
		$path = dirname(__FILE__).'/key_dealer.bin';
		if (file_exists($path)) {
			return file_get_contents($path);
		}
		// генерируем
		$key = '';
		for($i = 0; $i<1024; $i++) $key .= chr(rand(0, 255));
		file_put_contents($path, $key);
		return $key;
	}
	
	
	/**
	 * @return string
	 */
	static function GetWWWDir() {
		$request = Request::GetInstance();
		return $path = realpath($request->getHeader("DOCUMENT_ROOT").Request::GetInstance()->getBaseDir()).DS;
	}
	
	
	private $cacheDir = null;
	/**
	 * Get cache directory
	 *
	 * @return string
	 */
	public function getCacheDir() {
		if (is_null($this->cacheDir)) {
			$this->cacheDir = getcwd().DS."var".DS."cache".DS;
			if (!file_exists($this->cacheDir)) mkdir($this->cacheDir);
		}
		return $this->cacheDir;
	}
}