<?php

class Page extends CActiveRecord
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
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pages';
	}


	public function relations()
		{
					return array(
					'sections'=> array(self::BELONGS_TO, 'Page_sections', 'section'),
					'rubrics'=> array(self::BELONGS_TO, 'Page_rubrics', 'rubric'), 
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
				array('title, short_descr, creation_date', 'required'),
		);
	}

	/**
	 * @return array relational rules.
	 */

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'title' => 'Çàãîëîâîê',
			'short_descr' => 'Ñîäåðæàíèå',
			'creation_date' => 'Äàòà ñîçäàíèÿ',
		);
	}
	
	static public function section_list () {
			$connection = Yii::app()->db;
				/////////////////////////////Èíèöèàëèçèðóåì Ñåêöèè
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
	
		static public function  rubric_list(){
			$connection = Yii::app()->db;
			$query= "SELECT id, name FROM page_rubrics  ORDER BY id";
			$command=$connection->createCommand($query);
			$dataReader=$command->query();
			$sections_id[] = 0;
			$sections_names[]='...выбор';
			while(($row=$dataReader->read())!==false) {
			$sections_id[]=$row['id'];
			$sections_names[]=$row['name']; 
			}
			$section_data=array_combine($sections_id, $sections_names );
			return $section_data;
		}
}
