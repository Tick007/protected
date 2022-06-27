<?php

class Categoriestradex extends CActiveRecord///////////Это класс для работы с родными категориями трэйд-икса
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
			//'child_categories_3' =>array(self::MANY_MANY, 'Categoriestradex', 'categories(category_id, parent)'),
			'products'=> array(self::HAS_MANY, 'Products', 'category_belong'),
			'parent_categories'=>array(self::BELONGS_TO, 'Categories', 'parent'),
			'page'=> array(self::BELONGS_TO, 'Page', 'linked_page'),
			'products_in_bookmarks'=> array(self::MANY_MANY, 'Products', 'bookmarks(object_id,user_id)'),
			'articles'=> array(self::HAS_MANY, 'Page', 'category_id'),
			'characteristics_categories' => array(self::HAS_MANY, 'Characteristics_categories', 'categories_id'),
		);
	}
	
	
	public function move($parentId)
	{
		$this->parent = $parentId;
		$this->save();
		
		if($parentId == 0)
		{
			$path = unserialize($this->path);
			$this->path = serialize(array_merge(array($path[0]), FHtml::getRootBreadcrumb()));
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

}