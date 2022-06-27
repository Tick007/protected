<?php

class Categories extends CActiveRecord
{	

	//private $connection;
	//public $head_images; /////////////////////Тут будет храниться массив с фоками модели и бренда
	

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return 'categories';
	}


	public function relations()
		{
		return array(
			'child_categories'=> array(self::HAS_MANY, 'Categories', 'parent'),
			//'category_categories_compability'=> array(self::HAS_MANY, 'Category_categories_compability', 'compatible_category'),
			'childs'=> array(self::HAS_MANY, 'Categories', 'parent'),
			//'goods'=> array(self::HAS_MANY, 'Goods', 'category'),
			//'child_categories_3' =>array(self::MANY_MANY, 'Categoriestradex', 'categories(category_id, parent)'),
			'products'=> array(self::HAS_MANY, 'Products', 'category_belong'),
			'parent_categories'=>array(self::BELONGS_TO, 'Categories', 'parent'),
			'page'=> array(self::BELONGS_TO, 'Page', 'linked_page'),
		//	'products_via_category' =>array(self::MANY_MANY, 'Products', 'categories_products(group, product)'),
		);
	}


	public function move($parentId)
	{
		$this->parent = $parentId;
		$this->save();
		
		if($parentId == 0)
		{
			$path = unserialize($this->path);
			$this->path = serialize(array_merge(FHtml::getRootBreadcrumb(), array($path[0])));
		}
		else
		{
			$this->path = serialize(FHtml::get_productiya_path($this->category_id));
		}
		$this->save();
		
		$this->mapCategories(function($cat)
		{
			$cat->path = serialize(FHtml::get_productiya_path($cat->category_id));
			$cat->save();
		});
	}
	
	
	public function deleteChildCategories(){////////////Удаление детей
		$this->mapCategories(function($cat)
		{
			//echo $cat->category_id.'-'.$cat->category_name.':  ';
			Categories::deleteCategoryProducts($cat->category_id);
			$cat->delete();
		});		
	}//////public function deleteChildCategories(){
	
	public static function deleteCategoryProducts($category_id){
		//echo $category_id.'  ';
		$models=Products::model()->findAllByAttributes(array('category_belong'=>$category_id));
		//echo count($models).'<br>';
		if(isset($models)) for($i=0; $i<count($models); $i++) Products::DeleteProduct($models[$i]->id); 
	}///////////public static function deleteCategoryProducts
	
	public function mapCategories($hook = null)
	{
		if(!$hook)
		{
			$hook = function($category)
			{
				
			};
		}
		
		foreach($this->child_categories as $category)
		{
			$hook($category);
			if($category->child_categories)
			{
				
				$category->mapCategories($hook);
			}
		}
	}
	

	public static function getfulltree(){//////////Ds,jhrf dctuj lthtdf jlybv pfghjcjv
			
			$tree = array();
			$levels = array();
				
			$connection = Yii::app()->db;
			$query = "SELECT 
			t.category_id, 
			t.category_name, 
			t.alias,
			t.parent AS category_parent,
			t.path,
			t.show_category
			FROM `categories` `t` ";
			//$query.= "  GROUP BY t.show_category,  t.category_name, t.alias, t.parent , t.path ";
			//$query.= " ORDER BY t.parent ASC";
			$query.= "  ORDER BY t.sort_category";
			$command=$connection->createCommand($query)	;
			$dataReader=$command->query();
			$records=$dataReader->readAll();////
			
			
			foreach ($records as $k=>$v) { 
				$cat_id = $v['category_id'];
				$par_id = $v['category_parent'];
		        $current['parent_id'] = $par_id;
				$current['category_id'] = $cat_id;
				$current['alias'] = $v['alias'];
                $current['category_name'] = $v['category_name'];
				//$current['path'] = unserialize($v['path']);
				 $current['path'] = $v['path'];
				 if ( $v['category_parent'] == 0 ){
               		$tree[$v['category_id'] ] = $current;
					
              	} else {
         			$levels[$par_id]['children'][$cat_id ] = $current;
            	}
				
				///////////И делаем массивчик ид -> парент, что бы определить родительскую группу для заданной (если передана в гет (alias))
					$all_cats[$cat_id]=$par_id; ///////////////Вместо того  что бы делать при постороении дерева
					
        	}///////////foreach ($records as $k=>$v) {
		
        
		return array('tree'=>$tree, 'levels'=>$levels);		
		
	}//////public static function getfulltree(){//////////D
	
	public static function getCatalogTree(){
	
		$connection = Yii::app()->db;
		$query = "SELECT t.category_id,
			t.category_name,
			t.alias,
			t.parent,
			t.sort_category,
			t.linked_page
			FROM `categories` `t`  WHERE t.show_category = 1";
		$query.= "  GROUP BY t.category_id";
		$query.= " ORDER BY t.category_name";
		
		
		$command=$connection->createCommand($query)	;
		$dataReader=$command->query();
		$records=$dataReader->readAll();////
		
		
		foreach ($records as $k=>$v) {
			$current['parent_id'] = $v['parent'];
			$current['name'] = $v['category_name'];
			$current['category_id'] = $v['category_id'];
			$current['sort_category'] = $v['sort_category'];
			$current['alias'] = $v['alias'];
			$current['linked_page'] = $v['linked_page'];
			if ( $v['parent'] == 0){
				$tree[ $v['category_id'] ] = $current;
			} else {
				//if($v['linked_page']>0)	{//////////////16.10.2015 - что бы были группы только с описаниями
					$levels[$v['parent']]['children'][$v['category_id']] = $current;
				//}
			}
		}///////////foreach ($records as $k=>$v) {
		$models  = $tree;
		foreach ($models as $category_id=>$cat) {
			$treee[]=array(
					//'text'=>CHtml::link($cat['name'], array('catalog/group', 'alias'=>$cat['alias'])),
					'text'=>$cat['name'],
					'url'=>Yii::app()->createUrl('catalog/group', array('alias'=>$cat['alias'])),
					'id'=>$category_id,
					'alias'=>$cat['alias'],
					'children'=>Categories::children($category_id, $levels),
			);
	
		}/////////for ($i=0; $i<count($models); $i++) {
	
		/*
		echo '<pre>';
		print_r($treee);
		echo '</pre>';
		exit();
		*/
		
		return($treee);
	}
	
	private static function children($parent_id, $levels) {
		if (@isset($levels[$parent_id])) {
			$models = $levels[$parent_id];
			foreach ($models['children'] as $category_id=>$cat) {
				$treee[]=array(
						//'text'=>CHtml::link($cat['name'], array('catalog/group', 'alias'=>$cat['alias'])),
						'text'=>$cat['name'],
						'url'=>Yii::app()->createUrl('catalog/group', array('alias'=>$cat['alias'])),
						'id'=>$category_id,
						'alias'=>$cat['alias'],
						'children'=>Categories::children($category_id, $levels),
				);
			}
			return $treee;
		}//////if (@isset($this->levels[$parent_id])) {
		else return NULL;
	}//////////////private function children1($kladr_id, $code) {
	
	
	//////////Вытаскиваем список уникальных значений по характеристике в группе
	public static function getListofValuesByCateg_id($category_id, $caract_id){
		
		$query = "SELECT DISTINCT characteristics_values.value, characteristics_values.id_caract
			FROM characteristics_values	JOIN  products ON products.id = characteristics_values.id_product
			WHERE characteristics_values.value <>  '' 	AND characteristics_values.id_caract =$caract_id AND products.category_belong =  $category_id ORDER BY characteristics_values.value 
			";
			//$caract_list[] = NULL;
			//echo $query.'<br>';
			$connection = Yii::app()->db;
			$command=$connection->createCommand($query);
			$dataReader=$command->query();
			while(($row=$dataReader->read())!==false) {
				if(trim($row['value'])!='' AND $row['value']!=null)	 $caract_list[]=$row['value'];
			}
			if(isset($caract_list)) return $caract_list;
			else return null;
		
	}
	
	/**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('category_id',$this->category_id);
        $criteria->compare('category_name',$this->category_name,true);
        $criteria->compare('parent',$this->parent);
        $criteria->compare('show_category',$this->show_category);
        $criteria->compare('sort_category',$this->sort_category);
        $criteria->compare('guid1',$this->guid1,true);
        $criteria->compare('path',$this->path,true);
        $criteria->compare('alias',$this->alias,true);
        $criteria->compare('linked_page',$this->linked_page);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('keywords',$this->keywords,true);
        $criteria->compare('description',$this->description,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
	
	   /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'category_id' => 'Category',
            'category_name' => 'Category Name',
            'parent' => 'Parent',
            'show_category' => 'Show Category',
            'sort_category' => 'Sort Category',
            'guid1' => 'Guid1',
            'path' => 'Path',
            'alias' => 'Alias',
            'linked_page' => 'Linked Page',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'description' => 'Description',
        );
    }

}////////////class