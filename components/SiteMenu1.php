<?
class SiteMenu  {
var $qqq;
//class MainMenu extends CWidget

public function Draw(){
$this->qqq = "<a href='/index.php'><img src=\"".Yii::app()->theme->baseUrl."/images/right.png\" border=\"0\"/>&nbsp;Главная</a><br />";
//<a href='/index.php?r=product'>Каталог</a><br />
//<a href='/users/index.php?action=2'>Личный кабинет</a><br />";
echo $this->qqq;
//echo CHtml::link("<img src=\"".Yii::app()->theme->baseUrl."/images/right.png\" border=\"0\"/>&nbsp;Автосервис", '/page/autoservis').'<br>';
//echo CHtml::link("<img src=\"".Yii::app()->theme->baseUrl."/images/right.png\" border=\"0\"/>&nbsp;Про   тюнинг", '/page/protuning').'<br>';
//echo CHtml::link("<img src=\"".Yii::app()->theme->baseUrl."/images/right.png\" border=\"0\"/>&nbsp;Проекты", '/page/projects').'<br>';
//echo CHtml::link("<img src=\"".Yii::app()->theme->baseUrl."/images/right.png\" border=\"0\"/>&nbsp;Aкции", '/page/actions').'<br>';
}
}
?>