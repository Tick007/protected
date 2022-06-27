<?php
class MyShoppingCart {

private $order;////////Заказ
private $history;//////История просмотров

	 

			/*
			 * Нормальный новый метод для инициализации заказа в формате ид продукта = > количество
			 * */
			private function initializeCookie(){
				$cookie=Yii::app()->request->cookies['YiiCart'];
				if (isset($cookie)) $order=$cookie->value;
				if(isset($order) && trim($order))
				{
					$tokens = explode('#', $order);
					if(count($tokens)){
						foreach ($tokens as $tov_kol){
							$tov_kil_tok = explode(':', $tov_kol);
							if($tov_kil_tok[0]!='' && $tov_kil_tok[1]!='')$this->order[$tov_kil_tok[0]]=$tov_kil_tok[1];
					
						}
					}
				}
				
				$history_cook=Yii::app()->request->cookies['viewHistory'];
				if(isset($history_cook)) $history = $history_cook->value;
				if(isset($history) && trim($history)){
					$tokens = explode('_', $history);
					foreach ($tokens as $prod_id) {
						$this->history[]=$prod_id;
					}
				}
			}

			
			public function getHistory(){
				return $this->history;
			}
			
			public function addToHistory($prod_id){
				if(is_array($this->history)==false || (in_array($prod_id, $this->history))==false) $this->history[]=$prod_id;
				$this->saveHistory();
			}
			
			public function getOrder(){
				
				return $this->order;
			}

			function __construct($tovar_id=null, $num_to_basket=1){
				
				///////////////////////////Инициализировали из куки
				$this->initializeCookie(); 
				
				if($tovar_id!=null) $this->addToOrder($tovar_id, $num_to_basket);
			
			}//////////////////	public function __construct(){
			
			
			
			/** Внешний метод добавления к заказу
			 * @param int $tovar_id
			 * @param number $num_to_basket
			 */
			public function addToOrder($tovar_id, $num_to_basket=1){
				//////////////////////////Добавили/Удалил из заказа
				$this->addToCookie($num_to_basket, $tovar_id);
				
				//////////////////////////Сделали куки из заказа
				$this->SaveCookie();
			}
			
			
			private function addToCookie($num_to_basket, $tovar_id) {
				if(isset($this->order[$tovar_id])){
					unset($this->order[$tovar_id]);
				}
				else {
					$this->order[$tovar_id]=$num_to_basket;
				}

			}
			
			/**Изменяет количество товара в кукисах и сохраняет их
			 * @param int $tovar_id id продукта
			 * @param int $kol количество
			 */
			public function changeOrder($tovar_id, $kol){
				
				/*
				if(isset($this->order[$tovar_id])){
					$this->order[$tovar_id]+= $kol;
				}
				else
				*/
				if($kol==0 && isset($this->order[$tovar_id])) unset ($this->order[$tovar_id]);
				else $this->order[$tovar_id] = $kol;
				
				$this->SaveCookie();
			}
			
			
			private function saveHistory(){
				$cookie_str = '';
				if(isset($this->history) && empty($this->history)==false){
					$cookie_str = implode('_', $this->history);
				}
				
				$cookie =new CHttpCookie('viewHistory', $cookie_str); // sends a cookie
				//$cookie->expire= time()+60*60*24*30; ///////////30 дней
				Yii::app()->request->cookies['viewHistory']=$cookie;
			}
			
			private function SaveCookie() {
				
				$cookie_str = '';
				if(is_array($this->order) && empty($this->order)==false){
					foreach ($this->order as $tov=>$kol){
						$temp_order_1[]=$tov.':'.$kol;
					}
					if(isset($temp_order_1)) $cookie_str = implode('#', $temp_order_1);
					
				}

				
				$cookie =new CHttpCookie('YiiCart', $cookie_str); // sends a cookie
				$cookie->expire= time()+60*60*24*30; ///////////30 дней
				Yii::app()->request->cookies['YiiCart']=$cookie;
			}
			
			private function DeleteCookie() {
				$cookie =new CHttpCookie('YiiCart', NULL); // sends a cookie
				Yii::app()->request->cookies['YiiCart']=$cookie;
			}
}

?>