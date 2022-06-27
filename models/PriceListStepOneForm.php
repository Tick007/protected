<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class PriceListStepOneForm extends CFormModel 
{
	
	public $article_col;
	public $price_col;
	public $store_col;
	public $store_id;
	public $xlsprice;
	public $downloadedname;
	public $tempfile; ////////////Имя файла во временной папке
	 
	public function rules()
	{
		return array(
			// username and password are required
			// password needs to be authenticated
			
			array('article_col,  price_col, store_col, store_id', 'required'),
			array( 'xlsprice', 'checkxls'),
			//array('alias', 'check_unique'),
			//array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
			//array('email', 'email','message'=>'Введите корректный адрес электронной почты'),
			//array('site', 'CStringValidator', 'allowEmpty'=>true),
			//array('show_category, show_children_as_one','boolean'),
			//array('sort_category', 'numerical'),
			//array('title, h1, children_option_name, search_keywords, description, keywords', 'safe'),
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
			'article_cole'=>'Номер колонки артикла',
			'price_col'=>'Колонка цены',
			'store_col'=>'Колонка остаткова',
			'store_id'=>'Поставщик/склад',
			'xlsprice'=>'Файл прайслиста',
			
		);
	}



public function checkxls(){ ////////Проверка загруженного файла
	/*
	echo '<pre>'; 
	print_r($_FILES);
	echo '</pre>';
	*/
	if (isset($_FILES) AND isset($_FILES['PriceListStepOneForm']['tmp_name']) AND isset($_FILES['PriceListStepOneForm']['tmp_name']['xlsprice']) AND trim($_FILES['PriceListStepOneForm']['tmp_name']['xlsprice'])!='') {//////////Загрузка главной картинки
					//print_r($_FILES);
							$downloaded_file = $_FILES['PriceListStepOneForm'];
							//print_r($downloaded_file);
							//echo '<br>';
							 if ($downloaded_file['type']['xlsprice']=='application/vnd.ms-excel' OR $downloaded_file['type']=='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ) {
								 $this->downloadedname =  $downloaded_file['tmp_name']['xlsprice'];
							}
							else $this->addError('xlsprice','Файл не того типа.');
	}
	else  $this->addError('xlsprice','Файл не загружен.');

}/////////public function checkxls(){ ////////Пров
	

function movefiletotemrary($destinationfolder){//Грузим файл во временное местоположение
	$this->tempfile = time().'.xls';
	$destinationfile = $destinationfolder.$this->tempfile;
	//echo $destinationfile;
	@unlink($destinationfile);
	if(!@move_uploaded_file($this->downloadedname, $destinationfile)) $this->tempfile='';
}///////function movefiletotemrary(){//Г


}////////////
