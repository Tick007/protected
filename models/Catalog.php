<?php

class Catalog extends CActiveRecord
{
		var $connection;
		var $command;
		var $dataReader;
		var $row;
		public $list_of_child_groups;
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
		return 'categories';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	 
	 
	 public function relations()
		{
		return array(
			//'term_node'    => array(self::HAS_MANY, 'Term_node', 'nid'),
			//'term_node_belong'    => array(self::BELONGS_TO, 'Term_node', 'nid'),
			//'term_node_one'    => array(self::HAS_ONE, 'Term_node', 'nid'),
			//'files'=>array(self::MANY_MANY, 'Files', 'content_type_product(nid, field_ins_fid)'),
			//'uc_product_stock'=>array(self::MANY_MANY, 'Uc_product_stock', 'uc_products(nid, model)'),
			//'char_val'=>array(self::MANY_MANY, 'Characteristics_values', 'products(category_belong, id )'),/////////////////ytghfdbkmyj
			//'uc_product_stock' => array(self::HAS_ONE, 'Uc_product_stock', 'nid'),
		//	'node_revisions'    => array(self::HAS_MANY, 'Node_revisions', 'nid'),
		//	'node_revision_one'    => array(self::HAS_ONE, 'Node_revisions', 'nid'),
		//	'uc_products' => array(self::HAS_ONE, 'Uc_products', 'nid'),
		'parent_categories'=>array(self::BELONGS_TO, 'Categories', 'parent'),
			'parmodel'    => array(self::BELONGS_TO, 'Catalog', 'parent'),
			'childs' => array(self::HAS_MANY, 'Catalog', 'parent'),
			);
		}
 
	public function populate() {
	 		$this->connection = Yii::app()->db;
			$query="SELECT category_id, category_name, parent, show_category, sort_category FROM categories ";
   			$query.= " WHERE parent=".$this->category_id."  AND show_category=1";
  			$query.=" ORDER BY  sort_category";
			$this->command=$this->connection->createCommand($query);
			$this->dataReader=$this->command->query();
			while(($this->row=$this->dataReader->read())!==false) {
			$sub_cat_ids[]=$this->row['category_id'];
			$sub_cat_names[]=$this->row['category_name'];
	 		}
			
			if (is_array($sub_cat_ids) AND  is_array($sub_cat_name) ) return array_combine($sub_cat_ids, $sub_cat_names);
			else return array('0'=>'ничего нет, группа'.$this->category_id);
	 }///////////////////////////////
	 
	 public function char_list($category) {//////////Список характеристик для группы
	 		$this->connection = Yii::app()->db;
			$char_names=NULL;
			$char_ids=NULL;
			$char_list_of_values = NULL;
			$query = "SELECT caract_id, caract_name FROM characteristics 
			WHERE ( is_main=1 AND  caract_category =".$category.") OR (is_main=1 AND is_common=1)";
			$query.= " GROUP BY caract_id, caract_name  ORDER BY characteristics.caract_id";
			//echo  $query;
			$this->command=$this->connection->createCommand($query);
			$this->dataReader=$this->command->query();
			while(($this->row=$this->dataReader->read())!==false) {
			$char_ids[] =      $this->row['caract_id'];
			$char_names[]=$this->row['caract_name'];
			$char_list_of_values[] = $this->list_of_distinct_values($this->row['caract_id']);
			}
			//print_r($char_ids);
			//print_r($char_names);
			//$qqq =  array_combine($char_names, $char_ids);
			$qqq[0]=$char_ids;
			$qqq[1]=$char_names;
			$qqq[2]=$char_list_of_values;
			//print_r($char_list_of_values);
			return		$qqq;
	 }/////////////public function char_list($category) {
	 
	 private function list_of_distinct_values($caract_id) {
	 		$caract_list = NULL;
	 		$query = "SELECT DISTINCT characteristics_values.value, characteristics_values.id_caract
			FROM characteristics_values
			JOIN products ON products.id = characteristics_values.id_product
			WHERE characteristics_values.value <>  ''
			AND characteristics_values.id_caract =$caract_id";
			$connection = Yii::app()->db;
			$command=$connection->createCommand($query);
			$dataReader=$command->query();
			while(($row=$dataReader->read())!==false) {
			$caract_list[]=$row['value'];
			}
			return $caract_list;
	 }////////////function list_of_distinct_values($caract_id) {
			
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('id, category_name', 'required'),
		);
	}

}