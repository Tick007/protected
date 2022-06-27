<?
class ArticlesLinks extends CWidget {

var $chapter_id;
var $headtext;

	function __construct($chapter_id, $headtext){

		$this->chapter_id = $chapter_id;
		$this->headtext = $headtext;
	}


public function Draw() {
	
	$criteria=new CDbCriteria;
		$criteria->order = 't.id DESC LIMIT 10';
		$criteria->condition = " t.section = :section";
		$criteria->params = array(':section'=>$this->chapter_id);
		$models= Page::model()->findAll($criteria);
	
$this->render('articleslinks', array('models'=>@$models));
}










}///////////class Vitrina {
?>


