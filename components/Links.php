<?
class Links extends CWidget {

		
	public function __construct(){
	
	


     if (!defined('_SAPE_USER')){
        define('_SAPE_USER', 'b08e7449c7fba1182de7d253c8188832');
     }
     require_once($_SERVER['DOCUMENT_ROOT'].'/'._SAPE_USER.'/sape.php');
   //  $sape = new SAPE_client();

define('_SAPE_USER', 'b08e7449c7fba1182de7d253c8188832');
require_once($_SERVER['DOCUMENT_ROOT'].'/'._SAPE_USER.'/sape.php');
//Добавьте эти строки для вывода строки <!--check code-->


	
	$this->show_items();
	}
		
	public function show_items() {//////Вытаскиваем модули для правой колонки 
		/*
		$timeout = 1;
		$old = ini_set('default_socket_timeout', $timeout);
		$handle = @fopen("http://www.trade-x.ru/xml/links.php?host_id=22", "b");
		
		//stream_set_timeout($fp, 2);
		if ($handle) {
		$contents = '';
		while (!feof($handle)) {
		  $contents .= fread($handle, 8192);
		}
		fclose($handle);
		//echo "$contents";
		$xml = new SimpleXMLElement($contents);
		
		foreach ($xml->link as $link) {
			//$url[] = $link->url; 
			$url[] = iconv("UTF-8", "cp1251", $link->url); 
			$descr[] = iconv("UTF-8", "cp1251", $link->descr);
			//echo $link->descr."<br>";
		}
		*/
		
		$this->render('links', array('url'=>$url, 'descr'=>$descr));
		
		//}/////if ($handle) {
		
	}
	
		public function show_articles(){
$o[ 'force_show_code' ] = true;
		$sape = new SAPE_client($o);
		$sape_article = new SAPE_articles($o);
	 echo $sape_article->return_announcements();


		
	}
	
	
}
?>