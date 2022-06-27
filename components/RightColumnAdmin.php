<?
class RightColumnAdmin extends CWidget {
	var $connection;
		var $command;
		var $dataReader;
		var $row;
		
	public function __construct(){
	$this->show_items();
	}
		
	public function show_items() {//////Вытаскиваем модули для правой колонки
	echo "<div align=\"center\">";
	echo CHtml::link("<img src=\"/images/view_tree.png\" border=\"0\" width=\"50\">", '/adminproducts', $htmlOptions=array ('encode'=>false, 'title'=>'Товары' ) ) ;
//	echo '<hr>';
//	echo CHtml::link("<img src=\"/images/preferences-system.png\" border=\"0\" width=\"50\">", '/adminsettings', $htmlOptions=array ('encode'=>false, 'title'=>'Настройки' ) ) ;
	echo '<hr>';
	echo CHtml::link("<img src=\"/images/coins.png\" border=\"0\" width=\"50\">", '/adminprices', $htmlOptions=array ('encode'=>false, 'title'=>'Цены' ) ) ;
	echo '<hr>';
	echo CHtml::link("<img src=\"/images/cash_register.png\" border=\"0\" width=\"50\">", '/adminpayment', $htmlOptions=array ('encode'=>false, 'title'=>'Оплата' ) ) ;
	echo '<hr>';
	echo CHtml::link("<img src=\"/images/shoping_cart.png\" border=\"0\" width=\"50\">", '/adminorders', $htmlOptions=array ('encode'=>false, 'title'=>'Заказы' ) ) ;
	echo '<hr>';
	echo CHtml::link("<img src=\"/images/web.png\" border=\"0\" width=\"50\">", '/adminpages', $htmlOptions=array ('encode'=>false, 'title'=>'Страницы' ) ) ;
	echo '<hr>';
	echo CHtml::link("<img src=\"/images/kfm.png\" border=\"0\" width=\"50\">", '/admindocs', $htmlOptions=array ('encode'=>false, 'title'=>'Документы' ) ) ;
	echo '<hr>';
	echo CHtml::link("<img src=\"/images/users.png\" border=\"0\" width=\"50\">", '/roles', $htmlOptions=array ('encode'=>false, 'title'=>'Пользователи' ) ) ;
	echo '<hr>';
	echo CHtml::link("<img src=\"/images/report.png\" border=\"0\" width=\"50\">", '/adminreports', $htmlOptions=array ('encode'=>false, 'title'=>'Отчеты' ) ) ;
	echo '<br>';
	//echo CHtml::link("<img src=\"/images/kfm.png\" border=\"0\" width=\"50\">", 'http://'.$_SERVER['HTTP_HOST'].'/workflow/templates.php', $htmlOptions=array ('encode'=>false, 'title'=>'Шаблоны' ) ) ;
	echo "<br></div>";
	}
	
}
?>