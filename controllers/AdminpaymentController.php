<?php

class AdminpaymentController extends Controller //////////////Главный контроллер администрирования товаров
{
	const PAGE_SIZE=30;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='index';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;


	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'CheckAuthority +index, updatemethod, addmethod',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'list' and 'show' actions
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index', 'updatemethod', 'addmethod', 'bykladr'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions bykladr
				'actions'=>array('admin','delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function  filterCheckAuthority($filterChain)	{//////////Проверка, был ли передан файл
			//echo var_dump(Yii::app()->user->checkAccess('Правка товаров'));
			if (Yii::app()->user->checkAccess('Правка товаров') ) $filterChain->run();
			else {
			throw new CHttpException(401,'У вас нет прав');
			}
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

	public function actionIndex() {///////////////Вывод списка пользователей
		
		$method_id = Yii::app()->getRequest()->getParam('method_id', NULL);
		//echo $method;
		$models = PaymentMethod::model()->findAll();
		$payment_faces = PaymentFaces::model()->findAll();
		for($i=0; $i<count($payment_faces); $i++) $payment_face_list[$payment_faces[$i]->face_id]=$payment_faces[$i]->face;
			
		$this->render('index', array('models'=>$models, 'payment_face_list'=>$payment_face_list, 'method_id'=>$method_id) );
	}
	
	public function actionAddmethod(){
		$pm = new PaymentMethod;
		try {
							$pm->save();
								$url = Yii::app()->createUrl('/adminpayment/index', array('method_id'=>$pm->payment_method_id));
							$this->redirect($url, true, 301);	
							
						} catch (Exception $e) {
							 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}/////
	}
	
	public function actionUpdatemethod (){////////////////////////////Правка метода
		//print_r($_POST);
		$method_id = Yii::app()->getRequest()->getParam('method_id', NULL);
		$usluga_id = Yii::app()->getRequest()->getParam('usluga_id', NULL);
		if(isset($method_id)) {
			$METHOD = PaymentMethod::model()->findbyPk($method_id);
				if (isset($METHOD)) {
						$METHOD->payment_method_name = Yii::app()->getRequest()->getParam('payment_method_name', NULL);
						$METHOD->message = Yii::app()->getRequest()->getParam('message', NULL);
						$METHOD->payment_face = Yii::app()->getRequest()->getParam('payment_face', NULL);
						
						$method_enabled = Yii::app()->getRequest()->getParam('method_enabled', NULL);
						if ($method_enabled==NULL) $method_enabled = 0;
						$METHOD->enabled= $method_enabled;
						
						if (isset($usluga_id) AND intval($usluga_id)>0) {///////////Добавление номенклатуры в список возможных оплат
							$METHOD->nomenklatura_list = $METHOD->nomenklatura_list.'#'.$usluga_id;
						}/////////////if (isset($usluga_id)) {///////////Добавление номенклатуры в список возможных оплат					
						if(isset($_POST['del_tov'])) {//////////Удаление товаров из списка
								foreach($_POST['del_tov'] as $key=>$value) {/////////
									$nom_list = $METHOD->nomenklatura_list;
									//$METHOD->nomenklatura_list=str_replace("#".$key,'',$nom_list );
									$nomenklatura_array = explode('#',trim($nom_list));
									$num_products=count($nomenklatura_array);
									for ($k=0; $k<$num_products; $k++) {
										if(intval($nomenklatura_array[$k])>0 AND $key!=$nomenklatura_array[$k]) $new_arr[] = $nomenklatura_array[$k];
										
									}////////for ($k=0; $k<$num_products; $k++) {
									if (isset($new_arr)) $METHOD->nomenklatura_list=implode('#',array_unique($new_arr) );
									else $METHOD->nomenklatura_list='';
									
								}////////////////////////////////////////////////////
						}//////////////////if(isset($_POST['del_tov'])) {
						
						try {
							$METHOD->save();
						} catch (Exception $e) {
							 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}/////
				}//////////////if (isset($METHOD)) {
			
			
		
		}///////////////////if(isset($method_id)) {
		
		$this->redirect('/adminpayment/?method_id='.$method_id, true, 301);
	}///////////public functionUpdatemethod (){////////////////////////////Правка метода
	
	
	public function actionBykladr(){
		if (Yii::app()->user->checkAccess('Administrator'))  {
			$this->render('bykladr', array() );
		}
		else {
			throw new CHttpException(404, 'Не авторизован');
			exit();
		}
	}///////////public function actionBykladr(){
	
}
