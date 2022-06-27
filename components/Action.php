<?
class Action  extends CWidget{
//private $prod_char_id = 175;
function __construct(){
		
}



public function Draw($product_id = 588, $params=NULL) { 
				
				
				$criteria=new CDbCriteria;
				 $criteria->select=array( 't.*',  'picture_product.picture AS icon, ext ' );
				 $criteria->condition=" t.id  = :id ";
				 $criteria->join ="
	LEFT JOIN ( SELECT picture_product.product, picture_product.picture, pictures.ext AS ext FROM picture_product JOIN pictures ON pictures.id =picture_product.picture 	  WHERE is_main=1) picture_product ON picture_product.product = t.id  ";
				 $criteria->addCondition("t.product_visible = 1");
				 $criteria->params=array(':id'=>$product_id);
				$PRODUCT = Products::model()->find( $criteria);
				//$PRODUCT = Products::model()->findByPk($product_id);
								//if ($PRODUCT==NULL) throw new CHttpException(404,'Карточка не существует 2');

	

		if (isset($PRODUCT) ) $this->render('action', array('PRODUCT'=>$PRODUCT, 'params'=>$params) );
		
}///////////////public function Draw() {



}///////////class Vitrina {
?>


