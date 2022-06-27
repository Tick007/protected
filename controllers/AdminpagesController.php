<?php

class AdminpagesController extends Controller
{
	const PAGE_SIZE=30;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	 
		public function filters()
		{
				return array(
					'accessControl',
					'IsFileUploaded + process',////////////Применяем фильтр (для проверки прислан ли файл) только к process
					'CheckAuthority + list, show, edit, create, boxes, subscribe, sendnews, copypage',/////////////////////////проверка прав
			//		'CheckMenuName + details, updatemenu',  //////////////////Проверяем найдено ли меню по имени
				);
		}

	public function accessRules()
	{
		//Yii::app()->db->createCommand("SET NAMES cp1251")->execute();
			
			
		return array(
			array('allow',  // allow all users to perform 'list' and 'show' actions
			'actions'=>array('list','show', 'edit', 'create', 'boxes','subscribe', 'sendnews', 'copypage'),
			'users'=>array('@'),
			),	
			array('deny', // deny all users
			'users'=>array('*'),
			),
		);
	}

	public function  filterCheckAuthority($filterChain)	{//////////Проверка, был ли передан файл
			//echo var_dump(Yii::app()->user->checkAccess('Правка товаров'));
			if (Yii::app()->user->checkAccess('Edit Post') ) $filterChain->run();
			else {
			throw new CHttpException(401,'У вас нет прав для работы со страницами');
			}
	}
	
	public function init() {
        Yii::app()->layout = "admin";
    }

	public function actionSubscribe($id){///////////////Отображение списка кому отправлять


			$criteria=new CDbCriteria;
			$criteria->order="t.id DESC";
			//$criteria->condition = " t.active = :active";
			//$criteria->params=array(':active'=>1);
			$models = Clients::model()->findAll($criteria);//
			
			$new = Page::model()->findbyPk($id);
			
			$this->renderPartial('subscr-list', array('models'=>$models, 'nid'=>$nid, 'new'=>$new));
	}/////////////////public function actionSubscribe(){///////////////Отображен

	public function actionCopypage($id){///////////Копирование новости
		$source = Page::model()->findByPk($id);
		$destanation = new Page();
		//$destanation->setIsNewRecord();
		$attr = $source->getAttributes();
		unset($attr['id']);
		$d = date("Y-m-d H:i:s");
		$attr['creation_date']=$d;
		$attr['mod_date']=$d;
		$attr['active']=0;
		try {
					//print_r($attr);
					$destanation->save(false);
					$destanation->setAttributes($attr, false);
					
					$destanation->save(false);
		
					//print_r($destanation->attributes);
					//exit();
					if(isset($destanation->id)) {
						$this->redirect(Yii::app()->createUrl('/adminpages/list', array('section_id'=>$destanation->section)), true, 301);
						exit();
					}
			} catch (Exception $e) {
				  echo 'Caught exception: ',  $e->getMessage(), "\n";
			}//////////////////////
			
			
						//echo $destanation->id;

			
	}/////////////public function actionCopypage($id){///////////Ко

	public function actionSendnews() {////////////Отправка новостей
			//print_r($_POST);
			
			
			//echo 'werwe';
			
			$id = Yii::app()->getRequest()->getParam('id', NULL);
			$page_params = Yii::app()->getRequest()->getParam('Page', NULL);
			if ($page_params != NULL) $adresses = strip_tags($page_params['short_descr']);
			
			
			
			if ($id != NULL) $Page = Page::model()->findByPk($id);
			if (isset($Page) AND trim($Page->contents)) {
			
			
			
			if (isset($adresses) and is_array($adresses)) $adresses_list=explode(';', $adresses);
			else {
				foreach($_POST['emailtosend'] as $key=>$val)	 {
						if (isset($_POST['send'][$key])) {
								$adresses_list[$val]=$val;
						}
				}
			
			}
			//print_r($adresses_list);
			$send_mail = '';
			if (isset($adresses_list)) {
			foreach($adresses_list as $i=>$email) {
			//exit();
			
			//echo 'nid = '.$nid.'<br>';

			$headers= 'Content-type: text/html; charset=windows-1251' . "\r\n";
			$headers.= 'From: '.iconv( "UTF-8", "CP1251", $_SERVER['HTTP_HOST'])." "." <".Yii::app()->params['infoEmail']. ">\r\n".'Reply-To: '.Yii::app()->params['infoEmail']. "\r\n";		

					//$msg_body="<style>";
					/*
					ob_start();
					include($_SERVER['DOCUMENT_ROOT'].'/themes/'.Yii::app()->theme.'/css/main.css');
					$buf = ob_get_contents();
					ob_end_clean();
					$msg_body.=$buf;
					*/
					//$msg_body.="</style>";
							

					$msg_body=$this->renderPartial('newsbodymail', array('contents'=>$Page->contents), true);
					//if (mail($email, $Page->title,  iconv( "UTF-8", "CP1251",$msg_body), $headers)) $send_mail.=$email.'<br>';
					if (mail($email, iconv( "UTF-8", "CP1251",$Page->title),  iconv( "UTF-8", "CP1251",$msg_body), $headers)) $send_mail.=$email.'<br>'; 
					//if (mail($email, base64_encode($Page->title),  iconv( "UTF-8", "CP1251",$msg_body), $headers)) $send_mail.=$email.'<br>'; 
					//if (mail($email, iconv( "CP1251", "UTF-8",$Page->title),  iconv( "UTF-8", "CP1251",$msg_body), $headers)) $send_mail.=$email.'<br>'; 
					//echo $msg_body;

			
			}/////////foreach($adresses_list as $i=>$email) {
			}////////if (isset($adresses_list)) {
			}///////////if (isset($Page)) {
			if (trim($send_mail)) echo 'Отправленно на следующие адреса: <br>'.$send_mail;
	}//////////public function actionSendnews {////////////Отправка новостей

	public function actionShow()//////////////////вывод записи для редактирования
	{
//	$Page = $this->loadPost();
		$Page = Page::model()->findbyPk(Yii::app()->getRequest()->getParam('id'));
		$this->render('show',array('model'=>$Page, 'section_data'=>$this->section_data_func(), 'rubric_data'=>$this->rubric_data_func($Page->section) ));
	}
	
	public function actionBoxes() {//////////Вывод списка boxes из соответствующей таблицы
			$bid=intval(Yii::app()->getRequest()->getParam('bid'));
			if ($bid) {

			}
			$boxeslist = Boxes::model()->findAll();
			$this->render('boxes-list', array('boxeslist'=>$boxeslist));
	}//////////////public function actionBoxes() {//////////Вывод списка boxes из соответ

	public function actionCreate() /////////////////////Создание новой записи
	{		
			

	    
			$section_id = Yii::app()->getRequest()->getParam('section_id', 0);
			if ($section_id !='0') {
			//echo "Создаем.....<br>";
					$qqq = new Page();
					$qqq->title = 'Новая запись';
					$qqq->section=$section_id;
					//$qqq->dateGreate=date("Y-m-d H:i:s");
					$time1 = date("Y-m-d H:i:s");
					$qqq->creation_date = $time1;
					$qqq->mod_date = $time1;
					$qqq->active = 0;
					try {
							$qqq->save(false);
							} catch (Exception $e) {
								  echo 'Caught exception: ',  $e->getMessage(), "\n";
								  exit();
							}//////////////////////
							
					$this->redirect('/adminpages/'.$qqq->id."?section_id=".Yii::app()->getRequest()->getParam('section_id'), true, 301);
					
			}
	}
	 
	 public function actionEdit () {///////////////////////Правка записи active record
	//print_r($_POST);
	//print_r($_GET);
	//exit();
	 $page = Yii::app()->getRequest()->getParam('page') ;
	 $id = Yii::app()->getRequest()->getParam('id') ;
	 $alais = Yii::app()->getRequest()->getParam('alais') ;
	 $section_id = Yii::app()->getRequest()->getParam('section_id', 1);
	 $rubric = Yii::app()->getRequest()->getParam('rubric');
	  $sort = Yii::app()->getRequest()->getParam('sort', 1);
	  $creation_date = Yii::app()->getRequest()->getParam('creation_date');
	  if(isset($creation_date)){
	      
	  	$time =  explode('.', trim( $creation_date));
	  	//print_r($time);
	    //exit();
			$creation_date = $time[2].'-'.$time[1].'-'.$time[0].' '.date("H:i:s");
	  }
	  else $creation_date = date("Y-m-d H:i:s");
	  
	 // echo $creation_date;
	  //exit();
	 
	 if (isset($_POST['closepage'])) {
	$this->redirect("/adminpages/?section_id=".$section_id."&page=".$page, true, 301);
	 }
	
	 if (trim($id)) {
	 if($this->_model===null)
		{
			if($id!==null || isset($_POST))
				{ 
					$time = time();
					$Page=Page::model()->findbyPk($id);

					
					//print_r($_POST);
					if (isset($_POST['status'])) {
						$status = $_POST['status'];
					}////////////////
					else $status=0;
					//$Page->type = $_POST['type'];
					$Page->active=$status;
					$Page->title = $_POST['title'];
					$Page->name = Yii::app()->getRequest()->getParam('name') ;
					$Page->keywords=  trim($_POST['Page']['keywords']);
					$Page->description=  trim($_POST['Page']['description']);
					$Page->contents = trim($_POST['Page']['contents']);
					$Page->short_descr = trim($_POST['Page']['short_descr']);
					$Page->source = trim($_POST['source']);
					$Page->sort = $sort;
					$Page->mod_date = date("Y-m-d H:i:s");
					$Page->creation_date = $creation_date;
					if(isset($rubric))$Page->rubric = $rubric;
					else $Page->rubric = NULL;
					//$Page->category_id = $category_id;
					$Page->alais  = @trim($alais); 
					//echo $section_id;
					$Page->section = $section_id;
					try {
						$Page->save(false);
						} catch (Exception $e) {
  						 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}/////
															
				}/////////////////if($nid!==null || isset($_POST))
		}
	if (isset($_POST['save_close_page'])) {
			//$this->actionList();
			$this->redirect("/adminpages/?section_id=".Yii::app()->getRequest()->getParam('section_id')."&page=".$page, true, 301);
	}
	else $this->redirect('/adminpages/show?id='.$id."?section_id=".Yii::app()->getRequest()->getParam('section_id')."&page=".$page, true, 301);
	}//////////////if (trim($id)) {
	else $this->actionList();
	
	 }//////////////////
	 
	public function actionList()///////////////////////Вывод списка
	{
	
	
		$section_id = Yii::app()->getRequest()->getParam('section_id', NULL);
	//if (isset($_POST['section_id'])) $section_id = $_POST['section_id'];
	//else $section_id = 0;
	
		if (isset($_POST['apply'])) $this->applylist();
	
		$criteria=new CDbCriteria;
		
				
		if (Yii::app()->getRequest()->getParam('section_id', NULL) )  {
		//if (isset($_POST['section_id'])) {
		if($section_id != NULL)$criteria->condition='t.section = :section ';
		else $criteria->condition='t.section != 3';
		$criteria->order="t.id DESC";
		if($section_id != NULL) $criteria->params=array(':section'=>Yii::app()->getRequest()->getParam('section_id', NULL) );
		//$criteria->params=array(':section'=>Yii::app()->getRequest()->getParam('section_id', NULL) );
		$criteria->params=array(':section'=>Yii::app()->getRequest()->getParam('section_id', NULL) );
		}
		else {
	//	$criteria->condition='';
	//	$criteria->params=array('');//////////////Принудительно ограничиваем страницами
		}
			
		$pages=new CPagination(Page::model()->count($criteria));  //////////Produktciya - это таблица нодов
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);
		$pages->params=array('section_id'=>Yii::app()->getRequest()->getParam('section_id', NULL) );

		//if (isset($_POST['section_id'])) $models=Page::model()->findByAttributes(array('section'=>$_POST['section_id']));
		//else 
		$models=Page::model()->with('sections', 'rubrics')->findAll($criteria);
		
		
		if (isset($_POST['section_id'])) $model=Page::model()->findByAttributes(array('section'=>Yii::app()->getRequest()->getParam('section_id', NULL)));
		$section_data = $this->section_data_func() ;
		
		
		$this->render('list',array(
			'models'=>$models,
			'pages'=>$pages,
			//'section_data'=>Page::section_list(),
			'section_data'=>$section_data,
			'section_id'=>$section_id,
		));
	
	}/////////////function
	
	private function section_data_func() {
			$criteria=new CDbCriteria;
			//$criteria->condition='type=:section OR type=:section_news OR type=:section_faq';
			//$criteria->params=array(':section'=>'page' , ':section_news'=>'news', ':section_faq'=>'faq');///////////////Принудительно ограничиваем страницами
			$s_d = Page_sections::model()->findAll($criteria);
			$section_data[0]='...select';
			for($i=0; $i<count($s_d); $i++) $section_data[$s_d[$i]->id]=$s_d[$i]->section;		
			return $section_data;
	}////////////private function section_data() {
		
	private function rubric_data_func($section_id=NULL){
			$criteria=new CDbCriteria;
			$criteria->condition = "t.active = 1 ";
			$criteria->order="t.sorting";
			if($section_id!=NULL AND is_numeric($section_id) AND $section_id>0) {
				$criteria->addCondition("t.section_id = :section_id");
				$criteria->params=array(':section_id'=>$section_id);
			}
			$s_d = Page_rubrics::model()->findAll($criteria);
			$section_data[0]='...select';
			for($i=0; $i<count($s_d); $i++) $section_data[$s_d[$i]->id]=$s_d[$i]->name;		
			if(isset($section_data))return $section_data;
	}	/////////private function rubric_data_func(){
	
	private function applylist(){//////////////Действия по кноаке применить со спискои
			if (isset($_POST['del_page'])) {
					foreach ($_POST['del_page'] as $key=>$val) {
							$Page = Page::model()->findbyPk($key);
							if (isset($Page)) {
									try {
									$Page->delete();
									} catch (Exception $e) {
									 echo 'Caught exception: ',  $e->getMessage(), "\n";
									}/////
							}/////////if (isset($Page)) {
					}///////////foreach ($_POST['del_page'] as $key) {
			}/////////if (isset($_POST['del_page'])) {
	}////////////private function applylist(){//////////////Действия по кноаке п

	
	
	
	
		
	
}
