<?php

class VacancyController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	} 
	
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'CheckAuthority +admin',
			'CheckPath +index',
		);
	}
	
	
	function init(){
		$this->layout = 'main_index'; 
	}
	
	
	private function getVacancies($current=NULL){
	
			$criteria=new CDbCriteria;
			$criteria->condition="t.category_belong = :category_belong AND t.product_visible = 1 " ; 
			$criteria->order="t.id DESC" ; 
			if($current!=NULL) $criteria->addCondition('t.id <> '.$current);
			$criteria->params=array(':category_belong'=>Yii::app()->params['vacancies_root']);
			$models=Products::model()->findAll($criteria);
			return $models;
	}
	
	/*
	public function actionListall(){
		
		if(isset(Yii::app()->params['vacancies_root'])    ) {   
		 
		 
			$models= $this->getVacancies();
		
			$params=array();
			if(isset($models))$params['models']=$models;
			$this->render('list', $params);
		}
		else throw new CHttpException(404,'Ошибка конфигурации');
	}////////////public function actionListall(){
	*/
	
	
	public function actionVacancyinfo($id){
			
			$product=Products::model()->findByPk($id);
			if((isset($product) AND $product->product_visible!=1) OR isset($product)==false )  {
				 throw new CHttpException(404,'Вакансия более недоступна');
				exit();
			}
			
			$contact=new VacancyForm;
			if(isset($_POST['VacancyForm']))
			{
				
				$contact->attributes=$_POST['VacancyForm'];
				if($contact->validate())
				{
					//$headers="From: {$contact->email}\r\nReply-To: {$contact->email}";
					$headers="From: ".$contact->email."\r\n";
					$headers.='Content-type: text/html; charset=windows-1251' . "\r\n";
					
					
					$message=$this->renderPartial('mailtext', array('contact'=>$contact, 'product'=>$product), true);
					
					//echo $message;
					
					$possible_ext=array('doc', 'docx', 'rtf', 'pdf');
					
					foreach($possible_ext as $extension) {
					$new_file_name = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/temp/'.md5(Yii::app()->session->sessionId).'.'.$extension;
                    if(file_exists($new_file_name) AND is_file($new_file_name))	 {
							
							$atchname =  $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/temp/attachment.'.$extension;
							
							@rename($new_file_name, $atchname);
							
							$filename = $atchname;
							break;
						}
					}
					
					if(isset($filename)) $qqq = FHtml::XMail( Yii::app()->params['infoEmail'], Yii::app()->params['jobEmail'], 'На сайте оставленно резюме', $message, $filename);
					else @mail(Yii::app()->params['jobEmail'], iconv( "UTF-8", "CP1251",'На сайте оставленно резюме'), iconv( "UTF-8", "CP1251", $message),$headers);
					
					
					@unlink($filename);
					Yii::app()->user->setFlash('vacancy','Ваша заявка отправлена.');
					$contact=new VacancyForm;////////\Обнуляем
					//$this->refresh();
				}
			}
			
			
			$models= $this->getVacancies($id);
			
			
		
			$params=array();
			$params['contact']=$contact;
			if(isset($models))$params['models']=$models;
			if(isset($product))$params['product']=$product;
			$this->render('info', $params);
		
	}//////public function actionInfo($id){
	
	
	
	
	
	public function actionUploadimg(){ ////////////////загрузка фотки для организации
		//////////////Ajax загрузка файлов
		//echo 'files = ';
		//print_r($_FILES);
		//echo '<br>';
		//print_r($_POST);

		//$page = Yii::app()->getRequest()->getParam('page');
			
		//echo $page;
		//exit();
		/*
		$res = fopen($_SERVER['DOCUMENT_ROOT'].'/temp/'.Yii::app()->session->sessionId, 'w');
					fwrite($res, 'qweqweqwe');
					fclose($res);
				//	exit();
				*/
					

		if (isset($_FILES)) {
			//////////Загрузка картинки
			//print_r($_FILES);
			if (isset($_FILES['article_image'])) {
				$downloaded_file = $_FILES['article_image'];

				$downloaded_type = $_FILES['article_image']['type'];
				$downloaded_name = $_FILES['article_image']['name'];
				//doc=>application/msword
				//docx=>application/vnd.openxmlformats-officedocument.wordprocessingml.document
				//rtf=>application/msword
				//pdf=> application/pdf 
				$allowed_types = array('application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf');
				if(in_array($downloaded_type, $allowed_types)==false) {
					echo 'Недопустимый тип';
					exit();
				}

				if (trim($downloaded_file['tmp_name'])) {
					//////////////если файл был передан
					
					$fname_downl = $downloaded_file['tmp_name'];
					//echo $fname_downl.'<br>';
						
					$fname_parts=explode('.', $downloaded_name);
				
					$extension = $fname_parts[count($fname_parts)-1];
					
					
					//echo $extension;
					
						
					if (isset($extension)) {
							
						$pic_id_name1 =  md5(time()+$page+'_temp');
						
						$new_file_name = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/temp/'.md5(Yii::app()->session->sessionId).'.'.$extension;
						$new_file_name_noext = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/temp/'.md5(Yii::app()->session->sessionId).'.';
						//$new_file_name2 = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/add/'.$NEW_PICT->id.".";
						//@unlink($new_file_name);
						@unlink($new_file_name_noext.'docx');
						@unlink($new_file_name_noext.'doc');
						@unlink($new_file_name_noext.'pdf');
						@unlink($new_file_name_noext.'rtf');
						
						move_uploaded_file($downloaded_file['tmp_name'], $new_file_name);
							
						if(file_exists($new_file_name))	 {
							//echo 'Загружен:'.$new_file_name.'<br>';
							echo '<img src="/images/'.$extension.'.png" border="0"><br><br>';
						}
							
					}//////if (isset($extension)) {
				}////////if (isset($dowloaded_files['tmp_name'])) {
			}////////$_FILES['article_image']
		}////////////////////////if (isset($_FILES)) {//////////

	}/////////////public function actionUploadimg(){//////////////Aja
	
	
}