<?
class SiteMenu  {
var $qqq;
//class MainMenu extends CWidget

public function Draw(){
$this->qqq = "<a href='/index.php'><img src=\"".Yii::app()->theme->baseUrl."/images/right.png\" border=\"0\"/>&nbsp;�������</a><br />";
//<a href='/index.php?r=product'>�������</a><br />
//<a href='/users/index.php?action=2'>������ �������</a><br />";
echo $this->qqq;
//echo CHtml::link("<img src=\"".Yii::app()->theme->baseUrl."/images/right.png\" border=\"0\"/>&nbsp;����������", '/page/autoservis').'<br>';
//echo CHtml::link("<img src=\"".Yii::app()->theme->baseUrl."/images/right.png\" border=\"0\"/>&nbsp;���   ������", '/page/protuning').'<br>';
//echo CHtml::link("<img src=\"".Yii::app()->theme->baseUrl."/images/right.png\" border=\"0\"/>&nbsp;�������", '/page/projects').'<br>';
//echo CHtml::link("<img src=\"".Yii::app()->theme->baseUrl."/images/right.png\" border=\"0\"/>&nbsp;A����", '/page/actions').'<br>';
}
}
?>