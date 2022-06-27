<?php

class TradeXCache extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'User':
	 * @var integer $id
	 * @var string $username
	 * @var string $password
	 * @var string $email
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	 public $making_cache;
	 public $cache_expire;
	 
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tradexcache';
	}


	public function relations()
		{
					return array(
					//'sections'=> array(self::BELONGS_TO, 'Page_sections', 'section'),
					//'rubrics'=> array(self::BELONGS_TO, 'Page_rubrics', 'rubric'), 
					);
		}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				//array('title, short_descr, creation_date', 'required'),
		);
	}

	/**
	 * @return array relational rules.
	 */

	/**
	 * @return array customized attribute labels (name=>label)
	 */
/*
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'title' => 'Заголовок',
			'short_descr' => 'Содержание',
			'creation_date' => 'Дата создания',
		);
	}
	
	static public function section_list () {
			$connection = Yii::app()->db;
				/////////////////////////////Инициализируем Секции
			 $query= "SELECT id, section FROM page_sections  ORDER BY id";
			$command=$connection->createCommand($query);
			$dataReader=$command->query();
			$sections_id[] = 0;
			$sections_names[]='...выбор';
			while(($row=$dataReader->read())!==false) {
			$sections_id[]=$row['id'];
			$sections_names[]=$row['section']; 
			}
			$section_data=array_combine($sections_id, $sections_names );
			return $section_data;
	}
	*/
	
	public function beginCache($cach_name, $params) {
			$cache_id=md5($cach_name);
			if(isset($params['duration'])) $this->cache_expire =  microtime(true)+$params['duration'];
		
		
		$CONT = TradeXCache::model()->findByAttributes(array('id'=>$cache_id));
		
		//echo $CONT->expire.' - '.microtime(true).'<br>';;
		
		if(isset($CONT) AND $CONT->expire > microtime(true)) {////////////
			echo $CONT->value;
			return false;
		}
		else {
			if(isset($CONT)) $CONT->delete();
			$this->making_cache = $cache_id;
			ob_start();
			return true;
		}
	}
	
	public function endCache(){
		
		if(trim($this->making_cache)) {
		
			$buf = ob_get_contents();
			ob_end_clean();
			
			$CONT = new TradeXCache;
			$CONT->id = $this->making_cache;
			$CONT->value= $buf;
			$CONT->expire = $this->cache_expire;
			$CONT->save();
			echo $buf;
		}/////	if(trim($this->making_cache)) {
		
	}/////////public function endCache(){
	
	
	public static function clear_products_cache($cat_id){
		
				$connection =  Yii::app()->db;

				$query = "DROP TABLE IF EXISTS  products_".$cat_id;
				$command=$connection->createCommand($query)	;
				$dataReader=$command->query();
				
				
				$query = "DROP TABLE IF EXISTS  ostatki_trigers_".$cat_id;
				$command=$connection->createCommand($query)	;
				$dataReader=$command->query();
				
				
				$query = "DROP TABLE IF EXISTS  characteristics_values_".$cat_id;
				$command=$connection->createCommand($query)	;
				$dataReader=$command->query();
		
	}/////////public static function clear_products_cache($cat_id){
	
}
