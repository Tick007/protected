<?
class PrivateRoom extends CFormModel
{
		public $client_id;
		public $login;
		public $client_email;
		public $first_name;
		public $second_name;
		public $last_name;
		public $client_tels;
		public $client_post_index;
		public $client_passport;
		public $client_password;
		public $urlico;
		public $urlico_txt;
		public $client_country	;
		public $client_oblast	;
		public $client_district	;
		public $client_city	;
		public $client_street	;
		public $client_house	;
		public $client_korpus	;
		public $client_stroenie	;
		public $client_apart	;
		public $client_flore	;
		public $client_code	;
		public $client_entrance;
		public $client_comments;
		public $kontragent;
		public $card;
		
		public $conf_array;
		public $private_face=array('client_email', 'first_name', 'second_name', 'last_name',  'client_tels', 'client_post_index', 'client_passport', 'client_country', 'client_oblast', 'client_district', 'client_city',	'client_street',	'client_house',	'client_korpus',	'client_stroenie',	'client_apart',	'client_flore',	'client_code',	'client_entrance', 'client_comments', 'urlico_txt', 'login' , 'client_password');
		public $private_face_labels=array('client_email'=>'Email', 'first_name'=>'���', 'second_name'=>'�������', '��������','client_tels'=>'��������', 'client_post_index'=>'�������� ������', 'client_passport'=>'�����, � ��������, <br>���, ����� �����',  'client_country'=>'������', 'client_oblast'=>'�������', 'client_district'=>'�����', 'client_city'=>'�����', 'client_street'=>'�����', 'client_house'=>'���', 'client_korpus'=>'������', 'client_stroenie'=>'��������', 'client_apart'=>'��������', 'client_flore'=>'����', 'client_code'=>'���.��������', 'client_entrance'=>'�������' , 'client_comments'=>'�����������', 'urlico_txt'=>'������������ ��������');
		
		
		function __construct(){
			//$this->connection = Yii::app()->db;

		////////////////////////////////////////////////////////////////////////////////////////////////////////�������������� ������ � �����
					if (isset($_POST['PrivateRoom'])) 
					{
							$incoming = $_POST['PrivateRoom'];
							
							//while (list($key, $val) = each($incoming)) {
							foreach ($incoming as $key=> $val) {

									if (isset($key)) {
									if ($key != 'client_password') $this->$key = $val;
									else if($key == 'client_password') {
											if (trim($val)!='') $this->client_password = $val;
									}////////////////else if($key == 'client_password') {
									//echo $key.": ".$incoming[$key]."<br>";
									}
									else  $this->$key=NULL;	
							}
					}
					
					$this->CheckEnter();
					$this-> make_conf();
			}////////////////////////////function
		
			function CheckEnter(){
					if (is_numeric(Yii::app()->user->getId() )) {///////////////��.�. ������������ �����
					
							$this->client_id = Yii::app()->user->getId();
							$AR_Client  = Clients::model()->with('kontragent', 'card')->find('t.id=:ID', array(':ID'=>Yii::app()->user->getId() ));//
							if (!isset($_POST['saveclient'])) {
									
									foreach ($AR_Client->attributes as $key=>$value):
											if (in_array($key, $this->private_face) ) {
													$this->$key = $value;
													}
									endforeach;
															
							}////////if (!isset($_POST['saveclient'])) {
							$this->kontragent = $AR_Client->kontragent;		
							$this->card = $AR_Client->card;
							$this->login = $AR_Client->login;		
					  }

			}///////////function CheckEnter(){
	
			//public function orders_list() {
			//$query = "SELECT id, recept_date, summa_pokupok";
			//}//////////////
			
			private function make_conf() {
			$this->conf_array=array(
				 'title'=>'��������������� ����',
				 'showErrorSummary'  => true,
				  'elements'=>array(
					'client_email'=>array (
					'type'=>'text',
					'label'=>'',
					'maxlength'=>100,
					'value'=>isset($this->client_email) ? $this->client_email :  '',
					),
						'client_password'=>array(
						'type'=>'text',
						'maxlength'=>32,
						'label'=>'',
						'value'=>isset($this->client_password) ? $this->client_password :  '',
					),
					'first_name'=>array (
					'label'=>'',
					'type'=>'text',
					'maxlength'=>100,
					'placeholder'=>'First Name',
					'value'=>isset($this->first_name) ? $this->first_name:   '',
					),
					
					'second_name'=>array (
					'type'=>'text',
					'label'=>'',
					'maxlength'=>100,
					 'placeholder'=>'Second Name',
					'value'=>isset($this->second_name) ? $this->second_name:   '',
					),
					
					'last_name'=>array (
					'type'=>'text',
					'label'=>'',
					'maxlength'=>100,
					'placeholder'=>'Middle Name',
					'value'=>isset($this->last_name) ? $this->last_name:   '',
					),
					
					
					'client_tels'=>array (
					'type'=>'text',
					'label'=>'',
					'maxlength'=>150,
					'style'=>'width:200px',
					'value'=>isset($this->client_tels) ? $this->client_tels:   '',
					),
					
					'client_post_index'=>array (
					'type'=>'text',
					'label'=>'',
					'maxlength'=>10,
					'value'=>isset($this->client_post_index) ? $this->client_post_index :   '',
					),
					
					'client_passport'=>array (
					'type'=>'textarea',
					'label'=>'',
					'cols'=>'50',
					'rows'=>'4',
					'value'=>isset($this->client_passport) ? $this->client_passport :   '',
					),
					
					'client_country'=>array (
					'type'=>'text',
					'label'=>'',
					'maxlength'=>50,
					'value'=>isset($this->client_country) ? $this->client_country :   '',
					),
					
					'client_oblast'=>array (
					'type'=>'text',
					'label'=>'',
					'maxlength'=>100,
					'value'=>isset($this->client_oblast) ? $this->client_oblast :   '',
					),
					
					'client_district'=>array (
					'type'=>'text',
					'label'=>'',
					'maxlength'=>100,
					'value'=>isset($this->client_district) ? $this->client_district :   '',
					),
					
					'client_city'=>array (
					'type'=>'text',
					'label'=>'',
					'maxlength'=>100,
					'value'=>isset($this->client_city) ? $this->client_city :   '',
					),
					
					'client_street'=>array (
					'type'=>'text',
					'label'=>'',
					'maxlength'=>150,
					'value'=>isset($this->client_street) ? $this->client_street :   '',
					),
					
					'client_house'=>array (
					'type'=>'text',
					'label'=>'',
					'maxlength'=>10,
					'value'=>isset($this->client_house) ? $this->client_house :   '',
					),
					
					'client_korpus'=>array (
					'type'=>'text',
					'label'=>'',
					'maxlength'=>10,
					'value'=>isset($this->client_korpus) ? $this->client_korpus :   '',
					),
					
					'client_stroenie'=>array (
					'type'=>'text',
					'label'=>'',
					'maxlength'=>10,
					'value'=>isset($this->client_stroenie) ? $this->client_stroenie :   '',
					),
					
					'client_apart'=>array (
					'type'=>'text',
					'label'=>'',
					'maxlength'=>10,
					'value'=>isset($this->client_apart) ? $this->client_apart :   '',
					),
					
					'client_flore'=>array (
					'type'=>'text',
					'label'=>'',
					'maxlength'=>10,
					'value'=>isset($this->client_flore) ? $this->client_flore :   '',
					),
					
					'client_code'=>array (
					'type'=>'text',
					'label'=>'',
					'maxlength'=>25,
					'value'=>isset($this->client_code) ? $this->client_code :   '',
					),
					
					'client_entrance'=>array (
					'type'=>'text',
					'label'=>'',
					'maxlength'=>25,
					'value'=>isset($this->client_entrance) ? $this->client_entrance :   '',
					),
					
					'client_comments'=>array (
					'type'=>'textarea',
					'label'=>'',
					'cols'=>'50',
					'rows'=>'4',
					'value'=>isset($this->client_comments) ? $this->client_comments :   '',
					),
					
					'urlico'=>array (
					'type'=>'text',
					'label'=>'',
					'value'=>isset($this->urlico) ? $this->urlico :   '',
					),
					
					'urlico_txt'=>array (
					'type'=>'text',
					'label'=>'',
					'value'=>isset($this->urlico_txt) ? $this->urlico_txt :   '',
					),
				),
				'buttons'=>array(
        			'saveclient'=>array(
            		'type'=>'submit',
            		'label'=>'',
       				 ),
   				 ),
			
			);
			}////////////private function make_conf() {
		
				
				function GetStructure1() {
						return $this->conf_array;
				}////////////	function GetStructure1() {
				
				public function rules()
			{
				return array(
					// name, email, subject and body are required
					array('first_name, client_email, client_tels', 'required'),
					// email has to be a valid email address
					array('client_email', 'email'),
					// verifyCode needs to be entered correctly
				//	array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
				);
			}
			
			
			public function save_info() {
					$AR_Client  = Clients::model()->find('id=:ID', array(':ID'=>Yii::app()->user->getId() ));
					foreach($this->private_face AS $key){	
						if (isset($this->$key)) $AR_Client->$key = $this->$key;
					}
			$AR_Client->save();
			
			}////////////	public function save_info() {
}
?>