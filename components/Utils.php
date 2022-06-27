<?
class Utils   extends CWidget {


function __construct($title=''){ 
		//$this->title = $title;
}

function OrderInformPopupJS($view){
	$this->render($view.'/js');
}


function OrderInformPopup($view, $type='html'){
	
	/////////////////Вытаскиваем рандомный заказ
	$criteria=new CDbCriteria;
	//$criteria->select=array( 't.*',  'CONCAT_WS(".", picture_product.picture, picture_product.ext) AS icon', 'picture_product.picture AS icon_id' , 'picture_product.comments AS attribute_value');
	$criteria->condition=" t.contents_price > 0  AND belongs_product.category_belong NOT IN (10,393)";
	$criteria->order=" RAND() ";
	//$criteria->join =" LEFT JOIN ( SELECT picture_product.id, product, picture, ext, comments FROM picture_product JOIN pictures ON pictures.id=picture_product.picture WHERE is_vitrina=1) picture_product ON picture_product.product = t.id  ";
	$criteria->limit = 1;
	
	$model = OrderContent::model()->with('belongs_order', 'belongs_product')->find($criteria);
	
	if($model!=null){
		if($type=='html')$this->render($view.'/order_popup', array('model'=>$model));
		elseif($type='json'){
			$response =  array(
					'customer'=>$model->belongs_order->client->first_name.' '.$model->belongs_order->client->second_name,
					'product'=>$model->contents_name,
			);
			echo json_encode ( $response );
		}
	}
}//////function DrawHits
	
	


}///////////class Vitrina {
?>


