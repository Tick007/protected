<?
class Files extends CActiveRecord {///////////////////////////Класс в основном для работы с категориями машин

public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}


		public function tableName()
		{
			//return 'User';
			return 'files';
		}
	
		public function relations()
		{
		return array(
		//	'content_type_product'=> array(self::HAS_MANY, 'Content_type_product', 'field_ins_fid'),
		);
	}



}//////////////////// class Category extends
?>