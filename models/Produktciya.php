<?
class Produktciya extends CActiveRecord {///////////////////////////����� � �������� ��� ������ � ����������� �����

public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}


		public function tableName()
		{
			//return 'User';
			return 'produktciya';
		}
	
				public function relations()
		{
			return array(
				'page'    => array(self::BELONGS_TO, 'Page', 'linked_page'),

			);
		}



}//////////////////// class Category extends
?>