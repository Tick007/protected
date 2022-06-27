<?
class Page_rubrics extends CActiveRecord{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */

	public function tableName()
	{
		return 'page_rubrics';
	}
	
	
	public function rules()
	{
		return array(
			
			//array('minprice, maxprice, active, active_till_int, filters', 'exist'),
			//array('minprice, maxprice, active, active_till_int, filters', 'safe'),
			
		);
	}


	public function relations()
	{
		return array(
		
		'pages'   => array(self::HAS_MANY, 'Page', 'rubric'),
		
		);
	}
	
	
}////////class client  {

?>
