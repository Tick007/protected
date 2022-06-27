<?
class General_settings  extends CActiveRecord{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */

		 public function tableName()
	{
		return 'general_settings';
	}
	
	function GetStructure1() {
	
	$el_ar=array(
	
		'company_name'=>array(
            'type'=>'text',
			'label'=>'Наименование компании',
			//'value'=>'новая тема',
            'maxlength'=>10,
        ),
			
        	'company_tel'=>array(
            'type'=>'text',
			'label'=>'Контактный телефон',
			//'value'=>'новая тема',
          //  'maxlength'=>10,
        ),
		
			'list_view'=>array(
            'type'=>'text',
			'label'=>'Вид списка',
			//'value'=>'новая тема',
            'maxlength'=>10,
        ),
		
			'show_prices'=>array (
			'type'=>'dropdownlist',
			'items'=>array('0'=>'не выводить', '1'=>'Выводить'),
			'label'=>'Отображение цен',
		),
		
		'triggers_enabled'=>array (
			'type'=>'dropdownlist',
			'items'=>array('0'=>'Програмно', '1'=>'БД'),
			'label'=>'Использование тригеров остатков',
		),
		
		
		
		);
	
	return array(
		'showErrorSummary' => true,
	    'elements'=> $el_ar,
	
		'buttons'=>array(
		'savesettings'=>array(
        'type'=>'submit',
        'label'=>'Сохранить',
									),
    						),
					);
	}
	
	public function rules()
			{
				return array(
					// name, email, subject and body are required
					array('company_tel, company_name, list_view, show_prices, triggers_enabled', 'required'),
					// email has to be a valid email address
					//array('client_email', 'email'),
					// verifyCode needs to be entered correctly
				//	array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
				);
			}
			
		public function attributeLabels()
	{
		return array(
			'cat_gl' => 'Id',
			'login' => 'Имя',
			'passw' => 'Пароль',
			'list_view'=>'ewr',
			//'company_name'=>'company_name',
		);
	}

}////////class client  {

?>
