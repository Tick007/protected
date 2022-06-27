<?php

class AdminmediaController extends Controller //////////////Главный контроллер администрирования товаров
{
	const PAGE_SIZE=10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

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
	
	
	public function actions() {
			return array(
				'fileManager'=>array(
					'class'=>'application.extensions.elfinder.ElFinderAction',
				 ),
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
				'actions'=>array('create','filemanager', 'browse', 'yfb', 'addfile'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete', 'yfb'),
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

	public function actionList() {///////////////Вывод списка пользователей

			$fm=NULL;
		$this->render('index', array('fm'=>$fm) );
	}
	
	
		public function actionBrowse() {
    // я создал специально отдельный пустой лейаут
			//$this->layout='//layouts/clear';
			//$this->renderPartial('browser');
			$this->render('browser');
	}
	
	
	public function actionYfb(){////////////////Рисуем собственный файловый менеджер
			$folder= $gr_id = Yii::app()->getRequest()->getParam('folder', NULL);
			if ($folder!=NULL AND  is_dir($folder) ) {
			if ( $folder [ strlen( $folder ) - 1 ] != '/' )
				{
					$folder .= '/'; //добавляем слеш в конец если его нет
				}
			
				//echo $folder.'<br>';
				$nDir = opendir ($folder);
				
					while ( false !== ( $file = readdir( $nDir ) ) )
					{
					//echo $file.'<br>';
						if ( $file != "." AND $file != ".." )
						{
							if (!is_dir($folder.$file ) )
							{ 
								
								//если это не директория
								if (!preg_match('/.php/', $file) AND !preg_match('/.htaccess/', $file) AND !preg_match('/.bat/', $file)  )  {////////для всех кроме php
								$files [] = $file;
								}///////////////if (!preg_match('/.php/', $file) AND !preg_ma
							}
						}
					}
				if (count($files)>0) {
					sort ($files);
				}////////if (count($files)>0) {
			}/////////
			$this->render('yfb', array('files'=>@$files, 'folder'=>@$folder));
	}/////////////////////public function actionYfb(){////////////////Рисуем собс
	
	public function actionAddfile(){///////////////загрузка файла
	/*
	print_r($_FILES);
	echo '<br>';
	print_r($_POST);
	exit();
	*/
	$folder =  Yii::app()->getRequest()->getParam('folder', NULL);
	$delet_active = Yii::app()->getRequest()->getParam('delet_active', NULL);
	$active_item = Yii::app()->getRequest()->getParam('active_item', NULL);
	$ren_file_name = Yii::app()->getRequest()->getParam('ren_file_name', NULL);
	$create_folder = Yii::app()->getRequest()->getParam('create_folder', NULL); 
	
	if ($folder!=NULL AND  is_dir($folder) ) {
	if (isset($_FILES)) {//////////Загрузка главной картинки
							$downloaded_file = $_FILES['new_file'];
							if (trim($downloaded_file['tmp_name'])) {//////////////если файл был передан
							$fname = $this->trans($downloaded_file[name]);
							$new_file_name = $folder.$fname;
							//$new_file_name2 = $doc_root.'/pictures/add/'.$NEW_FILE->id.".";
							//echo 'fn = '.$new_file_name;
							@unlink ($new_file_name);
							move_uploaded_file($downloaded_file['tmp_name'], $new_file_name);
						}////////if (isset($dowloaded_files['tmp_name'])) {
				}////////////////////////if (isset($_FILES)) {//////////
	}////////////if ($folder!=NULL AND  is_dir($folder) ) {

	////////////////Удаление  файла
	//echo $folder.$active_item;
	if (file_exists($folder.$active_item) AND $delet_active==1) {
		//echo $folder.$active_item;
		@unlink ($folder.$active_item);
		//exit();
	}
	
	
	/////////////////////Переименование файла
	//print_r($_POST);
	if ($_POST['ren_file']==1 AND $ren_file_name!=NULL AND $active_item!=NULL AND is_dir($folder) ) {
			$new_name = $folder.$active_item;
			$old_name = $folder.$ren_file_name;
			rename($old_name, $new_name);
	}/////////////////////////////if isset($_POST['ren_file']) {
	
	////////////////////////////Создание папки
	$create_folder = trim(htmlspecialchars($create_folder));
	if ($create_folder!='' AND $folder ) {
			$path = $folder.$create_folder;
			@mkdir($path, 0755);
	}///////////if ($create_folder!='') {
	
	$this->redirect('/adminmedia/yfb/?folder='.$folder);
	}///////////public function actionAddfile(){///////////////загрузка файла
	
	
	private function letter_trans($str)
		{
			   $str = strtr($str,
				 "абвгдежзийклмнопрстуфыэАБВГДЕЖЗИЙКЛМНОПРСТУФЫЭ",
				 "abvgdegziyklmnoprstufieABVGDEGZIYKLMNOPRSTUFIE");
			   return strtr($str, array( 'е'=>"yo", 'х'=>"h", 'ц'=>"ts", 'ч'=>"ch", 'ш'=>"sh",
				 'щ'=>"shch", 'ъ'=>'', 'ь'=>'', 'ю'=>"yu", 'я'=>"ya",
				 'Е'=>"Yo", 'Х'=>"H", 'Ц'=>"Ts", 'Ч'=>"Ch", 'Ш'=>"Sh",
				 'Щ'=>"Shch", 'Ъ'=>'', 'Ь'=>'', 'Ю'=>"Yu", 'Я'=>"Ya"));
		}
	
	private function trans($str)
		{
			  $symbols = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
				'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
				'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '_', '.', '-');
			  $str = strtolower($this->letter_trans($str));
			  $str = str_replace(' ', '_', $str);
			  $str_result = '';
			  $len = strlen($str);
			  for ($i = 0; $i < $len; $i++)
			  {
				$s = $str[$i];
				if (in_array($s, $symbols))
				  $str_result.= $s;
			  }
			  return $str_result;
		}
	
}
