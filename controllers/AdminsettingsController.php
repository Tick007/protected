<?php

class AdminsettingsController extends CController
{ //////////////Гонтроллер общих настроек
	const PAGE_SIZE=10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	private $theme_id=3;

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;


	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'list' and 'show' actions
				'actions'=>array('details'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('templateparts', 'list', 'updatetemplate', 'product', 'product_update_main', 'product_update_img', 'product_update_charact', 'product_update_charact'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	
	public function init() {
        Yii::app()->layout = "admin";
    }
	
	public function get_productiya_path($parent){
				///////////////////////////////Вычисляем путь по дереву продукции
				$Path = Categoriestradex::model()->findbyPk($parent);
				$parent_id = $Path->parent;
				/////////Yii::app()->params->drupal_vars['taxonomy_catalog_level'] - нулевой уровень каталога
				$path_text = CHtml::link($Path->category_name, array('/adminproducts/','group'=>$Path->category_id), $htmlOptions=array ('encode'=>false));
				while ($parent_id>0) {
				
				$Path = Categoriestradex::model()->findbyPk($parent_id);
				$parent_id = $Path->parent;
				//if (trim($parent_id )=='')$parent_id =9;
				$path_text=CHtml::link($Path->category_name, array('/adminproducts/','group'=>$Path->category_id), $htmlOptions=array ('encode'=>false)).' -> '.$path_text;
				}///////while
				$path_text= CHtml::link('Список групп', '/adminproducts/', $htmlOptions=array ('encode'=>false)).' -> '.$path_text;
				return $path_text;
	}/////////////

	public function actionList() {////////////
	
		/////////////////////////////Сохранение формы
		$model =  General_settings::model()->findByPk(1);//
		if (isset($_POST['savesettings'])) {
				$model->attributes=$_POST['General_settings'];		
				$validate = $model->validate();
				if ($validate) {/////////Создаем запись
					$model->save();
				}
		}//////////if (isset($_POST['savesettings'])) {
	
	
		
		$form = new CForm($model->GetStructure1(), $model);
		
		$this->render('list', array('model'=>$model, 'form'=>$form) );
	}
	
	
	public function actionTemplateparts(){
			$criteria=new CDbCriteria;
			//$criteria->order = 'category_name';
			$criteria->condition = " t.theme_id =  ".$this->theme_id;
			$theme_chapters_files = Theme_chapters_files::model()->findAll($criteria);
					for ($i=0; $i<count($theme_chapters_files);$i++) {
							$block_status[$theme_chapters_files[$i]->chapter_id][$theme_chapters_files[$i]->file_id] = $theme_chapters_files[$i]->file_enabled;
							$block_position[$theme_chapters_files[$i]->chapter_id][$theme_chapters_files[$i]->file_id] = $theme_chapters_files[$i]->location;
							$block_rec[$theme_chapters_files[$i]->chapter_id][$theme_chapters_files[$i]->file_id] = $theme_chapters_files[$i]->rec_id;
							$block_sort[$theme_chapters_files[$i]->chapter_id][$theme_chapters_files[$i]->file_id] = $theme_chapters_files[$i]->sort;
					}
			
			$files  = Theme_files::model()->findAll();
			$chapters = Theme_chapters::model()->findAll();
			$this->render('template', array('chapters'=>$chapters, 'files'=>$files, 'block_status'=>$block_status, 'block_position'=>$block_position, 'block_rec'=>$block_rec, 'block_sort'=>$block_sort ));
	}///////////////public function actionTemplateparts(){
	
	
	public function actionUpdatetemplate(){////////////Апдейтим шаблон
			//print_r($_POST);
			//print_r($_POST['switchof']);
			
			if (isset($_POST['addfile'])) {
					$chapters = Theme_chapters::model()->findAll();
					foreach ($_POST['addfile']  as $file_to_add=>$val):
							for ($i=0; $i<count($chapters); $i++) {
							//echo $chapters[$i]->chapter_id.'<br>';
							//echo $file_to_add.'<br>';
							
										$theme_chapters_files = new Theme_chapters_files;
										$theme_chapters_files->isNewRecord=true;
										$theme_chapters_files->file_id= $file_to_add;
										$theme_chapters_files->chapter_id = $chapters[$i]->chapter_id	;
										$theme_chapters_files->theme_id = $this->theme_id;
										$theme_chapters_files->location = 'L';
										try {
											$theme_chapters_files->save(false);
											} catch (Exception $e) {
											 echo 'Ошибка начального сохранения параметра: ',  $e->getMessage(), "\n";
											}/////////////////////
							}	////////for ($i=0; $i<count($chapters); $i++) {
					endforeach;
					
			}////////////if (isset($_POST['addfile'])) {
			
			
			foreach ($_POST['switchof'] as $key=>$value):
				$theme_chapters_files = Theme_chapters_files::model()->findByPk($key);
				if ($theme_chapters_files != NULL)  {

					$theme_chapters_files->sort = $_POST['sort'][$key];
					$theme_chapters_files->file_enabled = $value;
					
					try {
										$theme_chapters_files->save(false);
										} catch (Exception $e) {
										 echo 'Ошибка сохранения: ',  $e->getMessage(), "\n";
										}//////////////////////
				}///////	if ($theme_chapters_files != NULL)  {
			endforeach;
			$this->redirect(Yii::app()->request->baseUrl.'/adminsettings/templateparts/');
			
	}/////////////////////	public function actionUpdatetemplate(){////////////Апдейтим шаблон

	
}
