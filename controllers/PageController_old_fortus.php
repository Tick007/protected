<?php

class PageController extends Controller
{
	const PAGE_SIZE=20;
	public $breadcrumbs=array();

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
			'accessControl', // perform access control for CRUD operations
		//	'loadPageNameAllias  + show',
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	 
	//public function __construct(){
	//	$this->parent->__construct();
	//}
	 
	public function accessRules()
	{
		//Yii::app()->db->createCommand("SET NAMES cp1251")->execute();
			
			
		return array(
			array('allow',  // allow all users to perform 'list' and 'show' actions
				'actions'=>array('list','show', 'faq'),
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Shows a particular model.
	 */
	//loadPageNameAllias
	
	
	
	public function  filterloadPageNameAllias($filterChain)	{//////////Поиск id пщ alais
			//echo var_dump(Yii::app()->user->checkAccess('Правка товаров'));
			if (Yii::app()->user->checkAccess('Правка товаров') ) $filterChain->run();
			else {
			throw new CHttpException(401,'У вас нет прав');
			}
		}
	
	public function init() {
       // Yii::app()->layout = "main";
    }
	
	public function actionFaq()
	{
		
		$models=Page::model()->findAllByAttributes(array('section'=>4, 'active'=>1));
		$this->render('faq',array('models'=>$models));
	}
	
	public function actionFaqrubrics()
	{
		$rubric = Yii::app()->getRequest()->getParam('id');
		
		$criteria=new CDbCriteria;
		$criteria->condition = " t.active =1 AND pages.active =1  AND t.section_id = 4 ";
		$criteria->order=" t.sorting, pages.creation_date";
		if(isset($rubric) AND is_numeric((int)$rubric)==true) {
			$criteria->addCondition("t.id = :rubric");
			$criteria->params=array(':rubric'=>$rubric);
		}
		$rubrics = Page_rubrics::model()->with('pages')->findAll($criteria);
			
			
		$criteria=new CDbCriteria;
		$criteria->condition = " t.active =1 AND pages.active =1 AND t.section_id = 4 ";
		$criteria->order=" t.sorting, pages.creation_date";
		$allrubrics = Page_rubrics::model()->with('pages')->findAll($criteria);	
			
		//$models=Page::model()->findAllByAttributes(array('section'=>4, 'active'=>1));
		$this->render('faq',array('rubrics'=>@$rubrics, 'allrubrics'=>@$allrubrics));
	}
	
	
	public function actionShow()
	{
		
		//echo Yii::app()->request->url;
		if(strstr(Yii::app()->request->url, 'regions') ){
			$this->layout="main_index";
			$this->render('regions',array('model'=>$this->loadPost())); 
		}
		$this->render('show',array('model'=>$this->loadPost()));
	}

	public function actionByalias (){/////////////Функция необходимая для загрузки страницы по алаису
			//print_r($_GET);
			$alais = Yii::app()->getRequest()->getParam('id', NULL);
			//echo $alais;
			$atr=array('alais'=>$alais);
			$Page=Page::model()->with('rubrics')->findByAttributes($atr);//
			
			
			if (isset($Page)) {
				
					if($Page->active!=1) {
						throw new CHttpException(404,'Страница отключена');
						exit();
					}
				
					$this->render('show',array('model'=>$Page));
			}
			else throw new CHttpException(404,'The requested page does not exist.');
	}/////////////public function actionByalias (){/////////////Функция необходимая для загрузки страницы по алаису

	public function actionFortusnews(){/////////////Функция необходимая для загрузки страницы по ид
			//print_r($_GET);
			$id = Yii::app()->getRequest()->getParam('id', NULL);
			//echo $alais;
			$Page=Page::model()->findByPk($id);//
			
			$years=$this->getNewsYears(1);
			
		
			if (isset($Page)) {
				
					$date = strtotime($Page->creation_date);
					$year = date( 'Y', $date);
					if(is_numeric($year))  $mounths = $this->getNewsMonths($year);
					
					$this->render('news_body',array('model'=>$Page, 'years'=>@$years, 	'mounths'=>@$mounths));
					
			}
			else throw new CHttpException(404,'The requested page does not exist.');
	}/////////////public function actionByalias (){/////////////Функция необходимая для загрузки страницы по алаису
	 
	 
	public function actionList()
	{
	
		if (isset($_POST['section_id'])) $section_id = $_POST['section_id'];
		else $section_id = 0;
		$year = Yii::app()->getRequest()->getParam('year');
		$mounth = Yii::app()->getRequest()->getParam('mounth');
	
	
		$criteria=new CDbCriteria;
		$criteria->order = " t.creation_date DESC ";
		$criteria->condition='section=:section AND active=1';
		if (isset($_POST['section_id'])) $criteria->params=array(':section'=>$_POST['section_id']);
		else $criteria->params=array(':section'=>1);/////////////1-новости
		if(isset($year) AND is_numeric($year)==true) {
			$criteria->addCondition("YEAR(t.creation_date) = :year");
			$criteria->params[':year']=$year;
		}
		if(isset($mounth) AND is_numeric($mounth)==true){
			$criteria->addCondition("MONTH(t.creation_date) = :mounth");
			$criteria->params[':mounth']=$mounth;
		}
		$pages=new CPagination(Page::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		//if (isset($_POST['section_id'])) $models=Page::model()->findByAttributes(array('section'=>$_POST['section_id']));
		//else 
		$models=Page::model()->findAll($criteria);
		
		if (isset($_POST['section_id'])) $this->_model=Page::model()->findByAttributes(array('section'=>$_POST['section_id']));
		
		
		
		$years=$this->getNewsYears(1);
		
		if(isset($year) AND is_numeric($year)==true) {///////////Выбираем месяца если задан год
				$mounths = $this->getNewsMonths($year);
		}/////////if(isset($year) AND is_numeric($year)==true) {///////
		
		//print_r($years);
		

		$this->render('list',array(
			'models'=>$models,
			'pages'=>$pages,
			'section_data'=>Page::section_list(),
			'section_id'=>$section_id,
			'years'=>@$years,
			'mounths'=>@$mounths,
		));
	}
	
	private function getNewsMonths($year) {
		$connection = Yii::app()->db;
		$query = "SELECT count( id ) AS num, MONTH ( `creation_date` ) AS month
						FROM `pages`
						WHERE active =1 AND section = 1 AND  YEAR(`creation_date`) = ".htmlspecialchars(trim(stripslashes($year)))."
						GROUP BY month
						ORDER BY month";
			$command=$connection->createCommand($query)	;
			$dataReader=$command->query();
			$mounths1 = $dataReader->readAll();
			if(isset($mounths1)) {///////////делаем массив по месяцам 
				for($i=0; $i<count($mounths1); $i++) $mounths[$mounths1[$i]['month']] =  $mounths1[$i]['num'];
			}////////if(isset($mounths1)) {///////////делаем массив по месяцам 
			if(isset($mounths)) return $mounths;
			else return array();
	}//////////private function getNewsMonths() {

	private function getNewsYears($section_id){
		//////////////Выбираем года и количество новостей 
		$connection = Yii::app()->db;
		$query = "SELECT count( id ) AS num, YEAR( `creation_date` ) AS year
						FROM `pages`
						WHERE active =1 AND section = $section_id
						GROUP BY year
						HAVING year >0  ORDER BY year";
		$command=$connection->createCommand($query)	;
		$dataReader=$command->query();
		$years = $dataReader->readAll();
		if(isset($years)) return $years;
		else return array();
	}//rivate function getNewsYears(){

	/**
	 * Manages all models.
	 */
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadPost($id=null)
	{
		$id = Yii::app()->getRequest()->getParam('id', Yii::app()->getRequest()->getParam('regid'));
		if($this->_model===null)
		{
			if($id!==null || isset($id))
				$this->_model=Page::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Executes any command triggered on the admin page.
	 */
	protected function processAdminCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete')
		{
			$this->loadUser($_POST['id'])->delete();
			// reload the current page to avoid duplicated delete actions
			$this->refresh();
		}
	}
	
	
	public function actionAsk(){////////////Форма для вопроса
	$this->layout = 'empty';
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			//echo $_POST;
			$model->attributes=$_POST['ContactForm'];
			//$model->verifyCode = 1;
			//$model->captcha = 1;
			$model->validators[2]->allowEmpty=true;
			if($model->validate()) 	{	
				
				
				$Page = new Page;
				$Page->title = 'вопрос: '.$model->subject;
				$Page->section = 4;
				$Page->active = 0;
				$Page->name = 'вопрос: '.$model->subject;
				$time1 = date("Y-m-d H:s:i");
				$Page->creation_date  = $time1;
				$Page->mod_date = $time1;
				$Page->contents = $model->body;
				$Page->short_descr = 'Вопрос от пользователя '.$mode->name.', '.$model->email;
				try {
							$Page->save(false);
							} catch (Exception $e) {
								  echo 'Caught exception: ',  $e->getMessage(), "\n";
							}//////////////////////
						
				
				$headers= 'Content-type: text/html; charset=windows-1251' . "\r\n";
				$headers.="From: {$model->email}\r\nReply-To: {$model->email}";
				//mail(Yii::app()->params['infoEmail'],$model->subject,$model->body,$headers);
				mail(Yii::app()->params['infoEmail'] ,$model->subject,$model->body,$headers);
				//mail('igor.ivanov@novline.com',$model->subject,$model->body,$headers);
				
				Yii::app()->user->setFlash('contact','Спасибо за обращение! Мы перезвоним вам при первой возможности.');
				$this->refresh();
			}//////////	if($model->validate())
		}
			$this->render('ask', array('model'=>$model));
	}/////////////////////public function actionAsk(){////////////Форма для вопроса
	
	public function actionCity(){///////////////////Вытаскиваем дилеров по городу
		$this->layout="main_index";
		$regid=Yii::app()->getRequest()->getParam('id2');

		$models = $this-> get_city_models($regid);
		
		if (isset($models) AND empty($models)==false) $this->render('map/dealers', array('models'=>$models));	
	
	}//////public function actionCity(){////////
	
	private function get_city_models($regid){
		$criteria=new CDbCriteria;
		$criteria->select=array( 't.*',  'picture_product.picture AS icon' );
		$criteria->condition="t.category_belong=:parent " ; 
		//$criteria->order="t.product_name" ; 
		$criteria->order="  Field(t.product_article, 'дилер','представитель','автодилер','техцентр')" ; 
		$criteria->params=array(':parent'=>$regid);
		 $criteria->join ="
LEFT JOIN ( SELECT id, product, picture FROM picture_product WHERE is_main=1) picture_product ON picture_product.product = t.id  ";
		$models=Products::model()->with('belong_category')->findAll($criteria);
		if(isset($models))return $models;
		else return array(); 
	}
	
	public function actionRegion(){//////////////Вытаскиваем список городов по области
		$this->layout="main_index";
		$regid=Yii::app()->getRequest()->getParam('id');
		$criteria=new CDbCriteria;
		$criteria->condition="t.parent=:parent AND t.show_category 	= 1" ; 
		$criteria->order="t.category_name" ; 
		$criteria->params=array(':parent'=>$regid);
		$cities=Categories::model()->findAll($criteria);
		
		$region = Categories::model()->findByPk($regid);
		
		//////////Перебираем города
		$models = array();
		if (isset($cities)) for($i=0; $i<count($cities); $i++) {
			$city_models = $this->get_city_models($cities[$i]->category_id);
			//echo count($city_models).'<br>';
			$models = array_merge($models, $city_models);
		}
		
		//echo count($models);	
		//exit();
		$this->render('map/cities', array('models'=>$models, 'region'=>$region));	
		 
	}////////public function actionRegion(){//////////////В
	
}////////////////////class
