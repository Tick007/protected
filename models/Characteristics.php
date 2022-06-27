<?php

class Characteristics extends CActiveRecord
{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'characteristics';
	}

	/**
	 * @return array validation rules for model attributes.
	 */

	/**
	 * @return array relational rules.
	 */
	public function relations()
		{
					return array(
					'values' => array(self::HAS_MANY, 'Characteristics_values', 'id_caract'),
					'value' => array(self::HAS_ONE, 'Characteristics_values', 'id_caract'),
					'characteristics_categories' => array(self::HAS_MANY, 'Characteristics_categories', 'characteristics_id'),
					//'suggestions' => array(self::HAS_MANY, 'Characteristics_help_suggestions', 'caract_id'),
					);
		}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	
	
	public function delete_option(){
		///////////////////Удаление опции (с почисткой всех хвостов)
	
		if(isset($this->caract_id)) {
			$connection = Yii::app()->db;
			$query = "DELETE FROM characteristics_values WHERE id_caract = :id_caract ";
			$command=$connection->createCommand($query);
			$command->params = array(':id_caract'=>$this->caract_id);
			$dataReader=$command->query();
			//print_r($dataReader);
			
			$query = "DELETE FROM characteristics_categories WHERE characteristics_id = :id_caract ";
			$command=$connection->createCommand($query);
			$command->params = array(':id_caract'=>$this->caract_id);
			$dataReader=$command->query();
			
			$query = "DELETE FROM characteristics WHERE caract_id = :id_caract ";
			$command=$connection->createCommand($query);
			$command->params = array(':id_caract'=>$this->caract_id);
			$dataReader=$command->query();
			
			
			///Можно и так
			// удалим строки, соответствующие указанному условию
			///Post::model()->deleteAll($condition,$params);
			
		}
	
	}///////public delete_option(){///////////////
	
}
