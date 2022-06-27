<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class CategoryForm extends CFormModel 
{
	
	public $category_name;
	public $parent;
	public $show_category;
	public $sort_category;
	public $alias;
	public $path;
	public $title;
	public $keywords;
	public $description;
	public $search_keywords;
	public $h1;
	public $children_option_name;
	public $show_children_as_one;
	
	private  $elements;////////////////Поля формы
	
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	 
	 
	public function rules()
	{
		return array(
			// username and password are required
			// password needs to be authenticated
			
			array('category_name,  alias', 'required'),
			array('alias', 'check_unique'),
			//array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
			//array('email', 'email','message'=>'Введите корректный адрес электронной почты'),
			//array('site', 'CStringValidator', 'allowEmpty'=>true),
			array('show_category, show_children_as_one','boolean'),
			array('sort_category', 'numerical'),
			array('title, h1, children_option_name, search_keywords, description, keywords', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			//'verifyCode'=>iconv( "UTF-8", "CP1251", 'Антиспам'),
			//'company'=>'Название организации:&nbsp;',
			'category_name'=>'Название',
			'show_category'=>'Включена',
			'sort_category'=>'Сортировка',
			'title'=>'Титл  группы',
			
		);
	}

	public function __construct($parent_id=0){
			
			$this->elements=array(
				'category_name'=>array(
					'type'=>'text',
					'value'=>$this->category_name,
					'maxlength'=>150,
				),
				
				'alias'=>array(
					'type'=>'text',
					'value'=>$this->alias,
					'maxlength'=>150,
				),
		
				'show_category'=>array(
					'type'=>'checkbox',
					
				),
				
				'sort_category'=>array(
					'type'=>'text',
					'value'=>$this->sort_category,
					'maxlength'=>3,
				),
				
				'title'=>array(
					'type'=>'text',
					'value'=>$this->title,
					'maxlength'=>255,
				),
				
				'title'=>array(
					'type'=>'text',
					'value'=>$this->title,
					'maxlength'=>255,
				),
				'h1'=>array(
					'type'=>'text',
					'value'=>$this->h1,
					'maxlength'=>255,
				),
				'keywords'=>array(
					'type'=>'text',
					'value'=>$this->keywords,
					'maxlength'=>255,
				),
				'description'=>array(
					'type'=>'text',
					'value'=>$this->description,
					'maxlength'=>512,
				),
				

				
				/*
				'parent'=>array (
				'type'=>'dropdownlist',
				'items'=>$this->get_tree(),
				'label'=>'Родительская группа',
				'selected'=>$parent_id, 
				//'onchange'=>"{form_checkout(this.id,this.value)}",
				),
				*/
			);
	
	}
	
	
function GetStructure() {
		return array(
			'showErrorSummary' => true,
			'elements'=> $this->elements,
		
		'buttons'=>array(
			'createit'=>array(
			'type'=>'submit',
			'label'=>'Сохранить',
			),
		),
		
		);
}

function get_tree() {
		
		$criteria=new CDbCriteria;
		$criteria->order = ' t.category_name ';
		$criteria->condition = " 	t.parent = 0";
		$all_groups = Categories::model()->with('child_categories')->findAll($criteria);//
		
		$all_groups_list[0]='Главная';
		for ($k=0; $k<count($all_groups); $k++) {
			$all_groups_list[$all_groups[$k]->category_id] = $all_groups[$k]->category_name;
			if (count($all_groups[$k]->child_categories)>0) {
				for($g=0; $g<count($all_groups[$k]->child_categories); $g++) {
					$all_groups_list[$all_groups[$k]->child_categories[$g]->category_id] = '---'.$all_groups[$k]->child_categories[$g]->category_name;
					$subcateg=Categories::model()->with('child_categories')->findAll('t.parent = '.$all_groups[$k]->child_categories[$g]->category_id);
					if (isset($subcateg)) {
						for ($p=0; $p<count($subcateg); $p++) {
							$all_groups_list[$subcateg[$p]->category_id] = '------'.$subcateg[$p]->category_name;
							if (isset($subcateg[$p]->child_categories)) {
								for ($m=0; $m<count($subcateg[$p]->child_categories); $m++) {
									///////
									$all_groups_list[$subcateg[$p]->child_categories[$m]->category_id] = '---------'.$subcateg[$p]->child_categories[$m]->category_name;
								}//////for ($m=0; $m<count(subcateg[$p]->child_categories); $m++) {
							}/////////if (isset($subcateg[$p]->'child_categories')) {
						}///////for ($p=0; $p<count($subcateg); $p++) {
					}//////////if (isset($subcateg)) {
				}////////for($g=0; $g<count($all_groups[$k]->child_categories); $g++) {
			}////////if (count($all_groups[$k]->child_categories)) {
		} //////////for ($k=0; $k<count($all_groups); $k++) {
		return $all_groups_list;
}/////////function get_tree() {

public function check_unique(){//////////////
		$cat_by_alias = Categories::model()->findByAttributes(array('alias'=>$this->alias));
		if ($cat_by_alias != NULL) $this->addError('alias','Алаис занят.');
}////////////////public function check_unique(){
	
}////////////
