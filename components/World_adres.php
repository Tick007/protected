<?php
class World_adres  extends CWidget {
	
	var $use_country;
	var $use_region;
	var $kladr_belongs;
	var $select_name;
	var $select_id;
	var $default_country_id = 3159;//////////////Россия
	var $countries_num;
	var $cities_list;
	var $update_metro;
	var $scriptfunc; ////////////Имя функции которую нужно выполнить при изменении города
	var $use_kladr;////////////использовать кладр а не id

	function __construct($use_country=false, $use_region=true, $kladr_belongs=NULL, $select_name, $select_id=NULL, $htmloptions=NULL, $update_metro=NULL, $scriptfunc=NULL,  $use_kladr=true){
			
			 $this->use_country = $use_country;
			 $this->use_region = $use_region;
			 $this->kladr_belongs = $kladr_belongs;
			 $this->select_name = $select_name;
			 $this->scriptfunc = $scriptfunc;
			 $this->use_kladr = $use_kladr;
			 if ($update_metro==1) $this->update_metro =1; ///////////////Признак обновлять поле метро, или нет
			 
			// echo 'kladr_belongs = '.$kladr_belongs;
			 
			 
			 if ($select_id==NULL) $this->select_id =  $this->select_name ;
			 else $this->select_id = $select_id;
			if (isset(Yii::app()->params['kladr_mode']['default_country'])) $this->default_country_id = Yii::app()->params['kladr_mode']['default_country'];
			 
			
			if ($this->kladr_belongs!=NULL) $curent_country_region = $this->find_country_region_id();
			if (isset($curent_country_region[0])) $curent_region = $curent_country_region[0];
			else $curent_region = NULL;
			if (isset($curent_country_region[1])) $curent_country = $curent_country_region[1];
			else $curent_country = NULL;
			
			//print_r($curent_country_region);
			
			$countries = $this->return_countries($curent_country);
			if ($this->use_region ==true) $country_regions = $this->return_country_region($curent_country, $curent_region);
			$region_cities = $this->return_region_cities($curent_region);
			
			$params_arr=array();
			if (isset($countries)) $params_arr['countries'] = $countries;
			if (isset($country_regions)) $params_arr['country_regions'] = $country_regions;
			$params_arr['region_cities'] = $region_cities;
			if(isset($htmloptions) AND $htmloptions!=NULL)$params_arr['htmloptions']  = $htmloptions;
			$this->render('/world_adres/region_select', $params_arr);

	}//////////////function __construct($menu_id){

	private function find_country_region_id(){////////////Поиск страны если задан идентификатор города
			//echo 'city = '.$this->kladr_belongs;
			$criteria=new CDbCriteria;
			$criteria->distinct = true;
			$criteria->condition= "t.kladr_id=:kladr_id ";
			$criteria->params=array(':kladr_id'=>$this->kladr_belongs);
			//print_r($criteria);
			$model= World_adres_cities::model()->find($criteria);
			if (isset($model)==false) {
					$criteria=new CDbCriteria;
					$criteria->distinct = true;
					$criteria->condition= "t.id = :kladr_id";
					$criteria->params=array(':kladr_id'=>$this->kladr_belongs);
					//print_r($criteria);
					$model= World_adres_cities::model()->find($criteria);
			}
			//echo $model->id;
			
			if (isset($model)) return array($model->region_id, $model->country_id);
			else return NULL;
	}////////////////private function find_country_id(){////////////П
	
	private function return_countries($curent_country=NULL){///////////Возвращаем страны
			$criteria=new CDbCriteria;
			//$criteria->condition = "";
			$criteria->order = "t.name";
			if ($this->use_country ==false AND isset($this->default_country_id) ) $criteria->addCondition ("t.id = ".$this->default_country_id);
			if ($this->use_country ==false AND $curent_country != NULL )  $criteria->addCondition ("t.id = ".$curent_country, 'OR');
			$models = World_adres_countries::model()->findAll($criteria);
			if (isset($models)) $this->countries_num = count($models);
			if(isset($models)) $list = CHtml::listdata($models, 'id', 'name');
			
			return CHtml::dropDownList('adres_country', $curent_country, $list, array(
					'ajax' => array(
					'type'=>'POST', //request type
					'url'=>Yii::app()->createUrl('nomenklatura/getregions'), //url to call.
					//Style: CController::createUrl('currentController/methodToCall')
					'update'=>'#adres_region', //selector to update
					//'data'=>'js:javascript statement' 
					//leave out the data key to pass all form values through
					),
					'id'=>'adres_country',
					'onchange'=>'{
						$("#'.$this->select_id.' option").remove();
						$("#adres_region option").remove();
						}',
					)); 
			
	}//////////private function return_countries(){
	
	private function return_country_region($curent_country, $curent_region){//////////Регионы/области страны
			$data=array();
			$criteria=new CDbCriteria;
			$criteria->order = "t.sort, t.name";
			$criteria->condition = "t.country_id = :country_id";
			if ($curent_country!=NULL)  $criteria->params=array(':country_id'=>$curent_country);
			elseif( $this->use_country==false) 	$criteria->params=array(':country_id'=>$this->default_country_id);
			else $criteria->params=array(':country_id'=>0);///////////Несуществующий идентификатор
			$models = World_adres_regions::model()->findAll($criteria);
			if (isset($models)) $data = CHtml::listdata($models, 'id', 'name');
			$data = array('0'=>'выбор..')+$data;
			return CHtml::dropDownList('adres_region', $curent_region, $data, array(
				'ajax' => array(
				'type'=>'POST', //request type
				'url'=>Yii::app()->createUrl('nomenklatura/getcities'), //url to call.
				//Style: CController::createUrl('currentController/methodToCall')
				'update'=>'#'.$this->select_id, //selector to update
				//'data'=>'js:javascript statement' 
				//leave out the data key to pass all form values through
				),
				'id'=>'adres_region',
				)); 

	}///////////private function return_country_region(){//////////Реги
	
	private function return_region_cities($curent_region) {
		if ($curent_region!=NULL){
				$models = World_adres_cities::model()->findAllByAttributes(array('region_id'=>$curent_region));
				if (isset($models)) {
					for($i=0; $i<count($models); $i++) {
							if ($models[$i]->kladr_id!=NULL AND $this->use_kladr==true) $data[$models[$i]->kladr_id]=$models[$i]->name;
							else $data[$models[$i]->id]=$models[$i]->name;
					}
					if (isset($data))$this->cities_list = $data;
				}///////////if (isset($models)) {//$data = CHtml::listdata($models, 'kladr_id', 'name');
				else $data=array(); 
		}
		else $data=array();
		//echo $this->kladr_belongs;
		if($this->update_metro==1) return CHtml::dropDownList($this->select_name, $this->kladr_belongs, $data, array('id'=>$this->select_id,
		'onchange'=>'{
						//$("#worldr_adres_selector").text($("#adres_region option:selected").text()+"->"+$("#'.$this->select_id.' option:selected").text());
						$("#worldr_adres_selector").text($("#'.$this->select_id.' option:selected").text());
						updatemetrooption(this);
						$("#region_select_ierachical").toggle();
						}',
					
		));///////////////////////если стоит признак обновления метро, то вызываем соответствующую функцию
		else return CHtml::dropDownList($this->select_name, $this->kladr_belongs, $data, array('id'=>$this->select_id,
		'onchange'=>'{
						//$("#worldr_adres_selector").text($("#adres_region option:selected").text()+"->"+$("#'.$this->select_id.' option:selected").text());
						$("#worldr_adres_selector").text($("#'.$this->select_id.' option:selected").text());
						$("#region_select_ierachical").toggle();'.$this->changeforFunc().'
						}',
					
		));
	}//////////private function return_region_cities($region_id==NULL) {
	
	private function changeforFunc(){
		if($this->scriptfunc!=NULL) return $this->scriptfunc;
	}
	
}///////////class Vitrina {
?>


