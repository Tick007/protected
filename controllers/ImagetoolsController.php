<?php

class ImagetoolsController extends Controller //////////////Главный контроллер администрирования товаров
{
	const PAGE_SIZE=10;
	var $product;

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
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'list' and 'show' actions
				'actions'=>array('podlojka', 'watermark'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('pricelist', 'list', 'contragents', 'kontragent','getgroupsoptions' , 'updatekagent', 'searchgoods', 'ajaxupload',  'searchchars', 'getcatchilds', 'indexgr', 'catcompatiblecat', 'getpricelistproducts'),
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
        Yii::app()->layout = "image";
		ini_set("memory_limit","512M");
    }
	
	public function actionWatermark(){
		
		
		$f = Yii::app()->getRequest()->getParam('f', NULL);
		$fname = Yii::app()->getRequest()->getParam('img', NULL);
		if($f!=null && trim($f)=='pi') $name = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_med/'.$fname;
		elseif($f!=null && trim($f)=='ai') $name = $_SERVER['DOCUMENT_ROOT'].'/pictures/add/icons/'.$fname;
		elseif($f!=null && trim($f)=='a') $name = $_SERVER['DOCUMENT_ROOT'].'/pictures/add/'.$fname;
		else $name = $_SERVER['DOCUMENT_ROOT'].'/pictures/img/'.$fname;
		$size = @getimagesize($name );
		if(strstr($fname,'.jpg'))$foto = imagecreatefromjpeg($name);
		elseif(strstr($fname,'.png'))$foto  = imagecreatefrompng($name);
		$width = $size[0];
		$height = $size[1];
		
		
		if($width>=1500 || $height>=1500)$watermark = $_SERVER['DOCUMENT_ROOT'].'/themes/'.Yii::app()->theme->name.'/images/water1500.png';
		else if( $height>=1000 && $height<1500 ) $watermark = $_SERVER['DOCUMENT_ROOT'].'/themes/'.Yii::app()->theme->name.'/images/water1000.png';
		else if( $height>=700 &&  $height<1000 ) $watermark = $_SERVER['DOCUMENT_ROOT'].'/themes/'.Yii::app()->theme->name.'/images/water700.png';
		else if ($height<700  &&  $height>=500 ) $watermark =$_SERVER['DOCUMENT_ROOT'].'/themes/'.Yii::app()->theme->name.'/images/water500.png';
		else if ( $height<500  &&  $height>=350 ) $watermark =$_SERVER['DOCUMENT_ROOT'].'/themes/'.Yii::app()->theme->name.'/images/water350.png';
		else if ( $height<250  &&  $height>=200 ) $watermark =$_SERVER['DOCUMENT_ROOT'].'/themes/'.Yii::app()->theme->name.'/images/water200.png';
		else  $watermark =$_SERVER['DOCUMENT_ROOT'].'/themes/'.Yii::app()->theme->name.'/images/water100.png';
		
		$size = @getimagesize($watermark);
		//print_r($size);
		$widthw = $size[0];
		$heightw = $size[1];
		$znak = imagecreatefrompng($watermark);
		
		
		
		$widthm = max(array($widthw, $width));
		$heightm = max(array($heightw, $height));		
		
		$widthmin = min(array($widthw, $width));
		$heightmin = min(array($heightw, $height));		
		
		///Imagecopyresampled ($im2, $im, 0,0,0,0,$new_width,$im_h_n,$im_width,$im_height);
	
		
	//	imagecopyresized($znak_resized, $transp, 0, 0,0,0, $width, $height, 1, 1);
		//imagecopyresized($znak, $znak, 0, 0,0,0, $width, $height, $widthw, $heightw);
		/*
		if($widthmin<$width AND $heightmin<$height) {
		$foto_resized = ImageCreateTruecolor($widthmin, $heightmin);
		
		imagecopyresampled($foto_resized,$foto, 0, 0, 0, 0, $widthmin, $heightmin, $width, $height);
		}
		else  $foto_resized = $foto;
		*/
		
		
		//$image = imagecreatetruecolor($width, $height);
		//$image_src = imagecreatefromjpeg($name); 
		
		//echo  $widthw;
		//exit();
		
		if($widthmin<=$width AND $heightmin<=$height) imagecopy($foto, $znak,(($widthm-$widthmin)/2),(($heightm-$heightmin)/2), 0, 0, $widthmin, $heightmin);
		else imagecopy($foto, $znak,0,0, 0, 0, $widthmin, $heightmin);
		
		//imagecopy($foto, $znak,(($widthm-$widthmin)/2),(($heightm-$heightmin)/2), 0, 0, $widthmin, $heightmin);
		
		//imagecopyresampled($image, $image_src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
		//imagejpeg($image, $new_image_name, 100);
		// уничтожаем изображения
		header('Content-Type: image/jpg');
		//header('image/jpeg');
		ImageJPEG($foto);
		//exit();
		Yii::$app->end();
		
	}

	public function actionPodlojka(){
		
		
		
		$fname = Yii::app()->getRequest()->getParam('fname', NULL);	
		$folder = Yii::app()->getRequest()->getParam('folder', NULL);	
		
		//echo $fname;

		$pw = Yii::app()->getRequest()->getParam('pw', NULL);	
		$ph = Yii::app()->getRequest()->getParam('ph', NULL);	
		
		$name = $_SERVER['DOCUMENT_ROOT'].'/pictures/'.$folder.'/'.$fname;
		$size = @getimagesize($name );
		
		
		$extension = $size['mime'];
		//echo  $name;
		//print_r($size);
		$width = $size[0];
		$height = $size[1];
		
		//if($width>$pw OR $height>$ph) {////////////////Ресайзим
		if ($extension=="image/jpeg") $im = imagecreatefromjpeg ($name);
		$im2 = ImageCreateTruecolor ($pw, $ph);
		
		//print_r($extension);
		
		$background = imagecolorallocate($im2, 255, 255, 255);
		imagefill ( $im2 , 0 ,  0 ,  $background );
		
		if($height>$width) {
				$ch_height = $height;
				$ch_width = $pw*$height/$ph;
				$normal_width = $ph*$width/$height;///////////////сколько будет занимать отмасштабированая часть на подложке
				
				//echo $normal_width;
				
				if($ch_width>$pw)$xofset = ($pw-$normal_width)/2;//////////////Центрируем
				else $xofset=0;
				$yofset=0;
		}
		elseif($height<=$width) {
			$ch_width = $width;
			$ch_height = $ph*$width/$pw;
			$normal_height = $pw*$height/$width;
			 $xofset=0;
			 $yofset=($ph-$normal_height)/2;
		}	
		
				
				
				Imagecopyresampled ($im2, $im,$xofset, $yofset,0,0,$pw,$ph,$ch_width,$ch_height);
				if($height>$width) imagefilledrectangle( $im2 , ($pw-$xofset-1) ,0, $pw,   $ph ,  $background );
				elseif($height<=$width)  imagefilledrectangle( $im2 , 0 , ($ph-$yofset-1)  , $pw,   $ph ,  $background );
				ImageDestroy($im);
		
		
		
		//}
		
		
		//$this->render('podlojka', array($im2));
		header('Content-Type: image/jpg');
		ImageJPEG($im2);
		exit();
	}
	
	
		
		

		
}///////////////////
