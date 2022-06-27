<?php
class SimplatoolsController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	public function init(){
		ini_set("post_max_size","12M");
		ini_set("upload_max_filesize","12M");
	}
	
	
	public function filters()
	{
		return array(
				'accessControl', // perform access control for CRUD operations
				'CheckBrouser +index,  error',
				'CheckAuthority +admin',
				//'CheckPath +index',
				//'SetTheme +index, contact, page, map',
		);
	}
	
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
	
	public function actionError()	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
				else 					$this->render('error', $error);
		}
	}
		
	public function actionPedit($id)
	{
		if(is_numeric($id)){
			$product = SimplaProducts::model()->with('descriptions')->findByPk($id);
			if($product!=null){
				
				
				
				$model=$product;
				
				// Uncomment the following line if AJAX validation is needed
				// $this->performAjaxValidation($model);
				
				if(isset($_POST['SimplaProducts']))
				{
					$model->attributes=$_POST['SimplaProducts'];
					if($model->save())
						$this->redirect(array('view','id'=>$model->id));
				}
				
				$this->render('update',array(
						'model'=>$model,
				));
				
				
			}
		}
		else {
			throw new CHttpException(404,'Неправильный идентификатор');
		}
	}
	
	public function actionPlist(){
		 $model=new SimplaProducts('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['SimplaProducts']))
            $model->attributes=$_GET['SimplaProducts'];

        $this->render('plist',array(
            'model'=>$model,
        ));
	}
	
	public function actionDlist($id){
		$model=new SProductsDescriptions('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SProductsDescriptions'])) $model->attributes=$_GET['SProductsDescriptions'];
		$model->product_id = $id;
		$this->render('dlist',array(
				'model'=>$model,
				'product_id'=>$id,
		));
	}
	
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreateDescription($id)
	{
		$model=new SProductsDescriptions;
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
	
		if(isset($_POST['SProductsDescriptions']))
		{
			$model->attributes=$_POST['SProductsDescriptions'];
			$model->product_id = $id;
			if($model->save())
				$this->redirect(array('dlist','id'=>$id));
		}
	
		$this->render('descriptions/create',array(
				'model'=>$model,
				'pid'=>$id,
		));
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdateDescription($id, $pid)
	{
		$saveimages = Yii::app()->getRequest()->getParam('yt1', null);
		$imagesortlist = Yii::app()->getRequest()->getParam('sort', null);
		$deletedbg = Yii::app()->getRequest()->getParam('deletedbg', null);
		$activedimg = Yii::app()->getRequest()->getParam('activedimg', null);
		$image_text = Yii::app()->getRequest()->getParam('image_text', null);
		
		
		$model=SProductsDescriptions::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		/////////////////////////////Удаление фотки фона блока
		if($deletedbg!=null){
			$fname = Yii::app()->params['simplatools']['productDescriptionsFolder']['path'].md5($model->id).'.jpg';
			@unlink ($fname );
		}
			
		if(isset($_POST['SProductsDescriptions']))
		{
			$model->attributes=$_POST['SProductsDescriptions'];
			if($model->save())
				//$this->redirect(array('UpdateDescription','id'=>$id, 'pid'=>$pid));
				$this->redirect(array('dlist','id'=>$pid));
		}
		elseif($saveimages!=NULL && $imagesortlist!=NULL){ /////////////// сохраянем сортировки фоток
			if (is_array($imagesortlist)){
				foreach ($imagesortlist as $descr_img_id=>$sortval){
					$descr_img = SProductsDescriptionsImages::model()->findByPK($descr_img_id);
					if($descr_img!=null){
						$descr_img->image_text = $image_text[$descr_img_id];
						$descr_img->sort = $sortval;
						if(isset($activedimg[$descr_img_id])) $descr_img->active = 1;
						else $descr_img->active = 0;
						$descr_img->save();
					}
					$descr_img = null;
				}
			}
		}
	
		////Смотрим текстовые блоки
		$dtext=new SProductsDescriptionsTexts('search');
		$dtext->unsetAttributes();  // clear any default values
		if(isset($_GET['SProductsDescriptionsTexts'])) $dtext->attributes=$_GET['SProductsDescriptionsTexts'];
		$dtext->description_id = $id;

		////Выбираем фотки
		if(isset($model->id)){
			$imagesrow = $model->images();
			//print_r($imagesrow);
			if(count($imagesrow)>0){
				foreach($imagesrow as $dimage){
					//echo
					$filename = Yii::app()->params['simplatools']['productDescriptionsImg']['path'].md5($dimage->id).'.'.$dimage->ext;
					if(file_exists($filename)){
						$images[]=array('img'=>$filename, 'id'=>$dimage->id, 'ext'=>$dimage->ext, 'sort'=>$dimage->sort, 
								'image_type'=>$dimage->image_type, 'active'=>$dimage->active, 'image_text'=>$dimage->image_text, 
								'url'=>Yii::app()->params['simplatools']['productDescriptionsImg']['url'].md5($dimage->id).'.'.$dimage->ext);
					}
					else $dimage->delete();
				}
			}
		}
		
		
		$params = array(
				'model'=>$model,
				'description_id'=>$id,
				'dtext'=>$dtext,
				'pid'=>$pid,
				
		);
		if(isset($images)) $params['images'] = $images;
		else $params['images'] = null;
		
		$this->render('descriptions/create',$params);
	}
	
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreatedescriptiontext($id, $pid)
	{
		$description_id = $id;
		
		$model=new SProductsDescriptionsTexts;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
	
		if(isset($_POST['SProductsDescriptionsTexts']))
		{
			$model->attributes=$_POST['SProductsDescriptionsTexts'];
			$model->description_id = $description_id;
			if($model->save())
				$this->redirect(array('updatedescription','id'=>$description_id, 'pid'=>$pid));
		}
	
		$this->render('texts/create',array(
				'model'=>$model,
				'pid'=>$pid,
				'did'=>$description_id
		));
	}
	
	
	public function actionUpdatedescriptiontext($id, $pid, $did){
		$description_id = $did;
		
		$model=SProductsDescriptionsTexts::model()->findByPk($id);
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['SProductsDescriptionsTexts']))
		{
			$model->attributes=$_POST['SProductsDescriptionsTexts'];
			$model->description_id = $description_id;
			if($model->save())
				$this->redirect(array('updatedescription','id'=>$description_id, 'pid'=>$pid));
		}
		
		$this->render('texts/create',array(
				'model'=>$model,
				'pid'=>$pid,
				'did'=>$did
		));
		
	}
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDeletedescriptiontext($id)
	{
		$model=SProductsDescriptionsTexts::model()->findByPk($id);
		$model->delete();
	
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	
	/** Загрузка фона для блока SProductsDescriptions
	 * @param int $id Идентификатор блока SProductsDescriptions
	 */
	public function actionUploadbg($id)
	{
		$description_id = $id;
		Yii::import("ext.EAjaxUpload.qqFileUploader");
			
		$folder=Yii::app()->params['simplatools']['productDescriptionsFolder']['path'];// folder for uploaded files
		//echo $folder;
		$allowedExtensions = array("jpg");//array("jpg","jpeg","gif","exe","mov" and etc...
		$sizeLimit = 10 * 1024 * 1024;// maximum file size in bytes
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUpload($folder, true, $description_id);
		$return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		$fileSize=filesize($folder.$result['filename']);//GETTING FILE SIZE
		$fileName=$result['filename'];//GETTING FILE NAME
	
		echo $return;// it's array
	}
	
	
	/** Загрузка изображений соответствующих записям SProductsDescriptionsImages
	 * @param int $id Идентификатор блока SProductsDescriptions
	 */
	public function actionUploaddimages($id){
		Yii::import("ext.EAjaxUpload.qqFileUploader");
		$description_id = $id;
		$folder=Yii::app()->params['simplatools']['productDescriptionsImg']['path'];// folder for uploaded files
		//echo $folder;
		$allowedExtensions = array("jpg");//array("jpg","jpeg","gif","exe","mov" and etc...
		$sizeLimit = 10 * 1024 * 1024;// maximum file size in bytes
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		
		$img = new SProductsDescriptionsImages;
		$img->description_id = $description_id;
		
		try {
			$img->save();
			$result = $uploader->handleUpload($folder, true, $img->id);
			$img->ext = $result['ext'];
			$img->save();
			$result['sort'] = $img->sort;
			$result['id'] = $img->id;
			$result['active'] = $img->active;
			$return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
			$fileSize=filesize($folder.$result['filename']);//GETTING FILE SIZE
			//$fileName=$result['filename'];//GETTING FILE NAME
			echo $return;// it's array
		} catch (Exception $e) {
			throw new CHttpException(500,'Что-то не так');
			exit();
		}
	
	}
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDeletedimage($id)
	{
		$img = SProductsDescriptionsImages::model()->findByPk($id);
		if($img!=null) {
			$fname = Yii::app()->params['simplatools']['productDescriptionsImg']['path'].md5($img->id).'.jpg';
			@unlink ($fname );
			$img->delete();
		}
		//$this->loadModel($id)->delete();
	
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		//if(!isset($_GET['ajax']))
		//	$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin')); 
		echo 'success';
	}
	
	
	
}