
<?php
class KladrSelect extends CWidget {////////////////////Рисует меню селекта

	private $SEARCH_CACH_EXPIRE=36000; ////sec
	public $data;
	
	public function __construct($elname='qqq', $region=NULL, $group=NULL, $htmloptions=NULL,$contragent_list = NULL){

		
		if (isset(Yii::app()->params['kladr_mode']['mode']) AND Yii::app()->params['kladr_mode']['mode']=='new' ) {///////рисуем справочник
				
				$section_data=array();
				
				$criteria=new CDbCriteria;
				$criteria->select="t.groupe";
				$criteria->distinct=true;
				//print_r($contragent_list);
				//exit();
				if (isset($contragent_list) AND $contragent_list!=NULL) $criteria->condition="t.id IN (".implode(',', $contragent_list).")";
				
				$models = Contr_agents::model()->findAll($criteria);
				if(isset($models)) for($k=0; $k<count($models); $k++) $kladr_ids_list[]=$models[$k]->groupe;
				
				if (isset($kladr_ids_list) AND empty($kladr_ids_list)==false) {
					//echo '<pre>';
				//print_r($kladr_ids_list);
				//echo '</pre>';
				//echo '<br><br>';
					//////////2. Выбираем регионы
					$criteria=new CDbCriteria;
					$criteria->condition="cities.kladr_id IN (".implode(',', $kladr_ids_list).")";// OR cities.id IN (".implode(',', $kladr_ids_list).")";
				///////////тут нужно далее проверку делать, присутствует ли найденый регион в списке регионов контрагентов
					$criteria->order = "t.sort, t.name";
					if (Yii::app()->params['kladr_mode']['use_country']==true)  {
					$regions = World_adres_regions::model()->with('cities', 'countries')->findAll($criteria);
						//////////////Проверяем что было найдено в городах относительно начального массива со списком kladr_id  - т.е. российские города
					if(isset($regions)) {
						for($i=0; $i<count($regions); $i++) {
							for($k=0; $k<count($regions[$i]->cities); $k++) {
								$kladr_id_cities_list[] = $regions[$i]->cities[$k]->kladr_id;
							}/////////for($k=0; $k<count($regions[$i]->cities); $k++) {
						}////////////for($i=0; $i<count($regions); $i++) {
							
							//print_r($kladr_id_cities_list);
						//	echo '<br><br>';
						
							
							
							//////////////Смотрим разность массивов
						if(isset($kladr_id_cities_list))$no_kladr_id_cities = array_diff ( $kladr_ids_list, $kladr_id_cities_list);
						else $no_kladr_id_cities = $kladr_ids_list;
						//print_r($no_kladr_id_cities);
						//echo '<br><br>';
						
						if(isset($no_kladr_id_cities) AND empty($no_kladr_id_cities)==false)	 { //////////и с этим результатом опрашиваем как cities.id
							$criteria=new CDbCriteria;
							$criteria->condition="cities.id IN (".implode(',', $no_kladr_id_cities).")";
							$criteria->order = "t.sort, t.name";
							$regions_no_kladr_id = World_adres_regions::model()->with('cities', 'countries')->findAll($criteria);
							
						}//if(isset($kladr_id_cities_list))	 { //////////и с этим р
						
						if(isset($regions_no_kladr_id) ) for($k=0; $k<count($regions_no_kladr_id); $k++) $regions[]=$regions_no_kladr_id[$k];
					}//////////if(isset($regions)) {
						
						
						if(isset($regions)==false) {///первая попытка по kladr_id не удалась
							$criteria=new CDbCriteria;
							$criteria->condition=" cities.id IN (".implode(',', $kladr_ids_list).")";
							$criteria->order = "t.sort, t.name";
							$regions = World_adres_regions::model()->with('cities', 'countries')->findAll($criteria);

						}//////if(isset($regions)==false) {
					}
					
					elseif(isset(Yii::app()->params['kladr_mode']['default_country'])) {
						$criteria->addCondition("t.country_id = ".Yii::app()->params['kladr_mode']['default_country']);
						$regions = World_adres_regions::model()->with('cities')->findAll($criteria);
					}
					
					//echo count($regions);
					
					//print_r(Yii::app()->params['kladr_mode']);
						
					if (isset(Yii::app()->params['kladr_mode']['use_country'])) 
					{	
						if (isset($regions) AND Yii::app()->params['kladr_mode']['use_country']==true ) {
							for ($k=0; $k<count($regions); $k++) {
									if (isset($regions[$k]->countries)) {
											if (isset($countries[$regions[$k]->countries->id])==false) $countries[$regions[$k]->countries->id]= $regions[$k]->countries;
											$country_region[$regions[$k]->countries->id][]=$regions[$k];
									}
							}
							
							
							if (isset($countries)) foreach ($countries as $country_id=>$country) {
									$section_data['cntid'.$country->id]=$country->name;
									$regions = $country_region[$country_id];
									
									for ($k=0; $k<count($regions); $k++) {
										if ($regions[$k]->kladr_id!=NULL) $section_data['rkladr'.$regions[$k]->kladr_id]='---'.$regions[$k]->name;
										else $section_data['rid'.$regions[$k]->id]='---'.$regions[$k]->name;
										
										if (isset($regions[$k]->cities)) for ($i=0; $i<count($regions[$k]->cities); $i++) {
											if ($regions[$k]->cities[$i]->kladr_id!=NULL) {
												if (isset($section_data['ckladr'.$regions[$k]->cities[$i]->kladr_id])==false) $section_data['ckladr'.$regions[$k]->cities[$i]->kladr_id]= '------'.$regions[$k]->cities[$i]->name;
											}
											else {
												if (isset($section_data['cid'.$regions[$k]->cities[$i]->id])==false) $section_data['cid'.$regions[$k]->cities[$i]->id]= '------'.$regions[$k]->cities[$i]->name;
											}
										}//////if (isset($regions[$k]->cit
									}//////for ($k=0; $k<count($regions); $k++) {
									
							}/////////	foreach ($countries as $country_id=>$country) {
							
							
					}//////if (isset($regions)) {
					elseif(isset($regions) AND Yii::app()->params['kladr_mode']['use_country']==false)	{////////else 2
												
							for ($k=0; $k<count($regions); $k++) {
								if ($regions[$k]->kladr_id!=NULL)$section_data['rkladr'.$regions[$k]->kladr_id]=$regions[$k]->name;
								else $section_data['rid'.$regions[$k]->id]=$regions[$k]->name;
								
								if (isset($regions[$k]->cities)) for ($i=0; $i<count($regions[$k]->cities); $i++) {
										if ($regions[$k]->cities[$i]->kladr_id!=NULL) {
									 		if (isset($section_data['ckladr'.$regions[$k]->cities[$i]->kladr_id])==false) $section_data['ckladr'.$regions[$k]->cities[$i]->kladr_id]= '---'.$regions[$k]->cities[$i]->name;
										}
										else if (isset($section_data['cid'.$regions[$k]->cities[$i]->id])==false) $section_data['cid'.$regions[$k]->cities[$i]->id]= '---'.$regions[$k]->cities[$i]->name;
								}
							}
					
					}//////else	{////////else 2/
						
				}
					//print_r($section_data);
					//$section_data = array();

					//$data = CHtml::listData($section_data,'id','text', 'group');
					//print_r($data);
					$section_data  = array(0=>'Все регионы') + $section_data;
					//echo '<pre>';
					//print_r($section_data);
					//echo '</pre>';
					echo CHtml::dropDownList($elname, $region, $section_data, $htmloptions );	
					$this->data = $section_data;			
				}/////if (isset($kladr_ids_list)) {
		}
		else{//////////if1
				
				
				$criteria=new CDbCriteria;
				$criteria->order = 't.sort';
				$criteria->join = "JOIN contr_agents ON contr_agents.groupe =  t.kladr_id AND contr_agents.alias IS NOT NULL";
				//if (isset($cas_arr))$criteria->condition="t.kladr_id IN (".implode(',', $cas_arr).")";
				if (is_numeric($group)) $criteria->condition = " products.category_belong = $group";
				$all_presents=Ma_kladr::model()->findAll($criteria);
				
				for ($h=0; $h<count($all_presents); $h++) {
					$main_codes[] = substr($all_presents[$h]->code, 0, 2);
					//$main_codes[] = substr($all_presents[$h]->code, 0, 2).'00000000000';
				}///////////////for ($h=0; $h<count($all_presents); $h++) {
				
				if (isset ($main_codes)) {///////
				
						$main_codes = array_unique($main_codes);
						$mc=count($main_codes);
				}////////////		if (isset ($main_codes)) {///////
				
				//////////Этот запрос работает пиздец как медленно, поэтому делаем кэш
				$rows =Yii::app()->cache->get(trim(serialize($main_codes).'kladr'));
				//var_dump($rows);
				if (isset($rows)==false OR empty($rows)==true) {
						//echo 'Новый запрос<br>';
						$criteria=new CDbCriteria;
						//$criteria->condition = " t.code='5000000000000' OR t.code='7700000000000' OR  t.code='4700000000000' OR  t.code='7800000000000' ";
						//$criteria->condition = " t.code='7700000000000' ";
							
						$s=0;
						$condition = '';
						if (isset($main_codes)) {
								
								foreach($main_codes as $id=>$code) {
									$s++;
									$condition .=" t.code='".$code."00000000000' ";
									if ($s<$mc) $condition .=" OR ";
								}/////////////
								
						//$condition = "t.code IN(".implode(",", $main_codes).")";
						$criteria->condition = $condition;
						$criteria->addCondition("t.socr = 'г' OR t.socr='обл' OR t.socr='Респ' OR t.socr='край' OR t.socr='Аобл' OR t.socr='округ'  OR t.socr='АО'  ");
						//echo $criteria->condition;
						}//////$main_codes
						$criteria->order = 't.sort';
						
							
						//$s_d = Contr_agents_groups::model()->with('child_categories')->findAll($criteria);
						if(trim($criteria->condition)!='')$s_d = Ma_kladr::model()->findAll($criteria);
						if (isset($s_d)) {
							//echo 'set<br>';
							//for ($k=0; $k<count($s_s); $k++) {
							//	$kladrlist[]=array('kladr_id=>');
							//}
							Yii::app()->cache->set(serialize($main_codes).'kladr', $s_d, $this->SEARCH_CACH_EXPIRE);
						}
						
				}///if (isset($rows)==false) {
				else {
						//echo 'prisvaivaem<br>';
						$s_d = $rows;
				}	
		
		
		
				$section_data[0]='не важно';
				if (isset($s_d)) {
						for($i=0; $i<count($s_d); $i++) {
							if ($s_d[$i]->socr=='г')  $section_data[$s_d[$i]->kladr_id]=$s_d[$i]->socr.'. '.$s_d[$i]->name;
							elseif($s_d[$i]->socr=='обл') $section_data[$s_d[$i]->kladr_id]=$s_d[$i]->name.' область';
							else $section_data[$s_d[$i]->kladr_id]=$s_d[$i]->name.' '.$s_d[$i]->socr.'.';
				
							$code1=substr($s_d[$i]->code, 0, 2);
							$criteria=new CDbCriteria;
							$regexp = " t.code REGEXP '^".$code1."0[0-9]{2}0[0-9]{2}' AND socr = 'г' ";
							$criteria->order = 't.name';
							$criteria->join = "JOIN products ON products.kladr_belongs = t.kladr_id ";
							$criteria->condition = $regexp;
							$s_d[$i]->child_categories=Ma_kladr::model()->findAll($criteria);
							if (count($s_d[$i]->child_categories)) {
								$subotdel =  $s_d[$i]->child_categories;
								for($k=0; $k<count($subotdel); $k++)  if($subotdel[$k]->kladr_id != '182296' AND $subotdel[$k]->kladr_id != '155352') {
											if ($subotdel[$k]->socr=='г') $section_data[$subotdel[$k]->kladr_id]='---г. '.$subotdel[$k]->name;
											else $section_data[$subotdel[$k]->kladr_id]=$subotdel[$k]->name;
									}//////////for($k=0; $k<count($subotdel); $k++)  if($s
							}
						}
				
					$section_data =  array(0=>'Все регионы')+$section_data;
					echo CHtml::dropDownList($elname, $region, $section_data, $htmloptions );		
				}////////////	if (isset($s_d)) {
		}///////////}///////////else{//////////if1
			
	

		
			
	}/////////////////function
	
	
	
}////////////////class Tree extends CWidget {
?>