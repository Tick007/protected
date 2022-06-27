<?
class PaymentMethod extends CActiveRecord{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */

	 public function tableName()
	{
		return 'payment_method';
	}
	
	
	public static function getPayments(){
		$models = PaymentMethod::model()->findAllByAttributes(array('enabled'=>1, 'payment_face'=>1));
		if($models!=null){
			foreach ($models as $pm){
				if($pm->nomenklatura_list!=null && trim($pm->nomenklatura_list)){
					$methods[$pm->payment_method_id]=array(
							'id'=>$pm->payment_method_id,
							'name'=>$pm->payment_method_name,
							//'payment_face'=>$pm->payment_face,
							'products'=>explode('#', $pm->nomenklatura_list),
					);
				}
				
			}
			
			//////////////Этап 2 формируем обратные массивы
			if(isset($methods)) foreach ($methods as $id=>$meth){
				foreach ($meth['products'] as $prod_id){
					$payments[$prod_id][$meth['id']]=$meth['name'];
				}
			}
			return $payments;
		}
	}
	
	public static function getPayMethods(){
	    $models = PaymentMethod::model()->findAllByAttributes(array('enabled'=>1));
	    if($models!=null){
    	        foreach ($models as $pm){
    	            $methods[$pm->payment_method_id]=$pm->payment_method_name;
    	                
    	        }
    	        return $methods;
	        }
	}
	
}////////class client  {

?>
