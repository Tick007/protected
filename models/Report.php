<?php

class Report {
		var $parametrs;
			
	public function sales(){////////////////Отчет по обороту
					
					//print_r($this->parametrs);
					
					if (isset($this->parametrs) AND @count($this->parametrs)>0) {
							foreach($this->parametrs as $parametr_name=>$value):
									($value=='on') ? $$parametr_name=1:$$parametr_name=$value;
									//echo $parametr_name.' = '.$$parametr_name.'<br>';
							endforeach;
					}/////////////if (isset($this->parametrs) AND @count($this->parametrs)>0) {
	
					//print_r($this->parametrs);
					
					$list_from =$this->parametrs[date_from_value];
					$list_upto =$this->parametrs[date_to_value];
	
					$query = "SELECT  parent_categories.category_name  AS sgr, ";
					if (@$detailed) $query.= " series_movement.id ,
					series_movement.operation_dt, 
					series.doc_id AS doc_id1,  
					series_movement.nds_out,  
					series_movement.nds_in,";
					 if (@$enable_store) $query.= " stores.name AS store_name, ";
					 $query.= "  SUM(series_movement.num) AS sum_num, 
					 round(series_movement.price_no_nds_in,2) AS price_no_nds_in, 
					 round(SUM(price_no_nds_in * series_movement.num),2) AS sum_no_nds_in,
					  SUM( ROUND( price_no_nds_in * series_movement.num  * series_movement.nds_in, 3 ) ) AS sum_nds_in,
					 round(price_no_nds_out,2) AS price_no_nds_out, 
					 round(SUM( (price_no_nds_out * series_movement.num ) ),2) AS sum_no_nds_out,
					SUM( ROUND( price_no_nds_out * series_movement.num * series_movement.nds_out, 3 )  )AS sum_nds_out,
					round(SUM(((price_no_nds_out - price_no_nds_in) * series_movement.num )  ),2)   AS pribil, 
					round(SUM(((price_no_nds_out - price_no_nds_in) * series_movement.num )  )/SUM(price_no_nds_in*series_movement.num),2)*100   AS pribil_ps,
					SUM(ROUND( (((series_movement.price_no_nds_out * series_movement.nds_out) - ( series_movement.price_no_nds_in * series_movement.nds_in ) ) * series_movement.num  ), 3)  ) AS nds_razn ";
					
					  if (@$child_nomenklat==1)  $query.= ", CONCAT_WS(',', products.product_name , product_attribute.attribute_value) AS product_name";
					  elseif (@$nomenklat==1)  $query.= ",  products.product_name ";
					  
					if (@$enable_buyer) $query.= ",   contr_agents.name  AS kname";
					if (@$movement_doc_id) $query.= ", series_movement.doc_id as doc_id2 ";
					if (@$enable_group_split) $query.= ", categories.category_name  AS gr "; 
					 if (@$enable_suplier==1) $query.= " ,co_original.name  AS supname";
			
			$query.= " FROM series_movement
			JOIN stores ON stores.id = series_movement.store_id
			JOIN series ON series.id = series_movement.series_id 
			JOIN  products ON  products.id = series_movement.product_id "; 
			
			if (@$child_nomenklat==1) $query.=" LEFT JOIN (SELECT id_product, GROUP_CONCAT( value) AS  attribute_value
			FROM `characteristics_values` JOIN (SELECT * FROM products WHERE product_parent_id >0) child_products ON child_products.id = characteristics_values.id_product GROUP BY id_product) product_attribute ON product_attribute.id_product = products.id ";
			
			$query.= " JOIN documents ON documents.id=series_movement.doc_id 
			JOIN contr_agents  ON contr_agents.id = series_movement.kontragent_id  
			LEFT JOIN   categories  ON categories.category_id = products.category_belong 
			LEFT JOIN  categories as  parent_categories ON categories.parent = parent_categories.category_id
			JOIN contr_agents suplier  ON suplier.id = series.kontragent_id 
			LEFT JOIN  series series_original ON  series_movement.original_series = series_original.id 
			LEFT JOIN documents docs_original ON docs_original.id=series_original.doc_id 
			LEFT JOIN contr_agents  co_original ON co_original.id = series_original.kontragent_id  
			WHERE documents.id>0 ";
			if (!@$doc_id)  $query.= "  AND documents.doc_type = 2    ";
			if (@$doc_id) $query.= "  AND documents.id=$doc_id ";
			if (@$sgroup ) $query.= "  AND parent_categories.category_id=$sgroup ";
			if (@$group ) $query.= "  AND categories.category_id=$group ";
			if (@$list_from  AND !@$doc_id)  {
			$list_from_sql=Yii::app()->GP->get_sql_date($list_from, "00:00:00");
			$query.="  AND series_movement.operation_dt>='$list_from_sql'   ";
			}
			
			if ( $list_upto AND !@$doc_id)  {
			//echo $list_upto;
			$list_upto_sql=Yii::app()->GP->get_sql_date($list_upto, "23:59:59");
			//echo $list_upto_sql;
			$query.="   AND series_movement.operation_dt<='$list_upto_sql' ";
			}
			
			$query.= " GROUP BY parent_categories.category_name ";
					if (@$detailed) $query.= ", series_movement.id, 
					 series_movement.operation_dt, 
					 series.doc_id ";
					 if (@$child_nomenklat==1)  $query.= ", products.id ";
					 if (@$nomenklat==1)  $query.= ", products.product_name ";
					if (@$enable_buyer) $query.= " , contr_agents.name  ";
					if (@$movement_doc_id) $query.= ", series_movement.doc_id  ";
					if (@$enable_group_split) $query.= ", categories.category_name  ";
					 if (@$enable_store) $query.= ", stores.name  ";
					 if (@$enable_suplier==1) $query.= " , co_original.name";
					 $query.= " ORDER  BY ";
					
					if (@$sort_order) {
					
					
			$query.= "  ";
			if ($sort_order==1) $query.= "  SUM(price_no_nds_in * series_movement.num)  DESC";
			else if ($sort_order==2) $query.= "  SUM(series_movement.num) DESC";
			else if ($sort_order==3) $query.= "  SUM(((price_no_nds_out - price_no_nds_in) * series_movement.num )  )  DESC";
			//else if ($sort_order==4) $query.= " dbo.Продажи.[Дата док]";
			else if ($sort_order==5) $query.= " SUM(((price_no_nds_out - price_no_nds_in) * series_movement.num )/(price_no_nds_in*series_movement.num))  DESC ";
			else if ($sort_order==6 AND @$enable_suplier==1) $query.= "  co_original.name ";
			else  $query.= " sgr ";
			}
			else  {
			$query.= " sgr ";
					if (@$enable_group_split) $query.= ", gr";  
					}
			
			//echo $query;
					
				$connection =   Yii::app()->db;		
				$command=$connection->createCommand($query)	;
				$dataReader=$command->query();
				$rows=$dataReader->readAll();		
				return $rows;
		}////////////////public function sales(){////////////////Отчет по обороту
		
		
		public function movement(){////////////////Отчет по обороту
					
					//print_r($this->parametrs);
					
					if (isset($this->parametrs) AND @count($this->parametrs)>0) {
							foreach($this->parametrs as $parametr_name=>$value):
									($value=='on') ? $$parametr_name=1:$$parametr_name=$value;
									//echo $parametr_name.' = '.$$parametr_name.'<br>';
							endforeach;
					}/////////////if (isset($this->parametrs) AND @count($this->parametrs)>0) {
	
					//print_r($this->parametrs);
					
					$list_from =$this->parametrs[date_from_value];
					$list_upto =$this->parametrs[date_to_value];
					if ($goodlist != NULL) $usluga_id = explode('#', $goodlist);
					
					$query2 = "SELECT products.id, 
					  CONCAT_WS(',', products.product_name , product_attribute.attribute_value) AS product_name,  
					   series.arrive_dt, series.id AS series_id,
					   series.num, series.doc_id , stores.name , document_types.type
					FROM products
					JOIN series ON series.product_id = products.id  
					JOIN stores ON series.store_id = stores.id 
					JOIN  categories  ON categories.category_id = products.category_belong 
					LEFT JOIN  categories  parent_categories ON categories.parent = parent_categories.category_id
					JOIN documents ON documents.id = series.doc_id
					JOIN document_types ON document_types.id = documents.doc_type ";
					$query2.=" LEFT JOIN (SELECT id_product, GROUP_CONCAT( value) AS  attribute_value
					FROM `characteristics_values` JOIN (SELECT * FROM products WHERE product_parent_id >0) child_products ON child_products.id = characteristics_values.id_product GROUP BY id_product) product_attribute ON product_attribute.id_product = products.id ";
					$query2.=" WHERE products.id >0  ";
					if (@$list_from  )  $query2.="  AND series.arrive_dt>='".Yii::app()->GP->get_sql_date($list_from, "00:00:00")."'  ";
					if (@$list_upto  )  $query2.="   AND series.arrive_dt<='".Yii::app()->GP->get_sql_date($list_upto, "23:59:59")."' ";
					if (@$sgroup ) $query2.= "  AND parent_categories.category_id=$sgroup ";
					if (@$group ) $query2.= "  AND categories.category_id=$group ";
					if (@$usluga_id) $query2.= "  AND products.id IN (".implode(",", $usluga_id).")";
					  $query2.=" ORDER BY series.product_id, series.store_id,  series.arrive_dt";

				$connection =   Yii::app()->db;		
				$command=$connection->createCommand($query2)	;
				$dataReader=$command->query();
				$rows=$dataReader->readAll();		
				return $rows;
		
		}////////////////public function movement движение по складам
		
		public function movement2() {/////// движение по складам 2 (нижняя часть таблицы)
		
					if (isset($this->parametrs) AND @count($this->parametrs)>0) {
							foreach($this->parametrs as $parametr_name=>$value):
									($value=='on') ? $$parametr_name=1:$$parametr_name=$value;
									//echo $parametr_name.' = '.$$parametr_name.'<br>';
							endforeach;
					}/////////////if (isset($this->parametrs) AND @count($this->parametrs)>0) {
	
					//print_r($this->parametrs);
					
					$list_from =$this->parametrs[date_from_value];
					$list_upto =$this->parametrs[date_to_value];
					if ($goodlist != NULL) $usluga_id = explode('#', $goodlist);
		
				  $query2= " SELECT products.id, 
				  CONCAT_WS(',', products.product_name , product_attribute.attribute_value) AS product_name,  
				   series_movement.operation_dt, series_movement.id AS   movement_id, series_movement.num, 
				  series_movement.doc_id , stores.name , document_types.type
				FROM products
				JOIN series_movement ON series_movement.product_id = products.id  
				JOIN stores ON series_movement.store_id = stores.id 
				JOIN  categories  ON categories.category_id = products.category_belong 
				LEFT JOIN  categories  parent_categories ON categories.parent = parent_categories.category_id
				JOIN documents ON documents.id = series_movement.doc_id
				JOIN document_types ON document_types.id = documents.doc_type ";
				$query2.=" LEFT JOIN (SELECT id_product, GROUP_CONCAT( value) AS  attribute_value
				FROM `characteristics_values` JOIN (SELECT * FROM products WHERE product_parent_id >0) child_products ON child_products.id = characteristics_values.id_product GROUP BY id_product) product_attribute ON product_attribute.id_product = products.id ";
				$query2.=" WHERE products.id >0  ";
				if (@$list_from  )  $query2.="  AND series_movement.operation_dt >='".Yii::app()->GP->get_sql_date($list_from, "00:00:00")."'  ";
				if (@$list_upto  )  $query2.="   AND series_movement.operation_dt <='".Yii::app()->GP->get_sql_date($list_upto, "23:59:59")."' ";
				if (@$sgroup ) $query2.= "  AND parent_categories.category_id=$sgroup ";
				if (@$group ) $query2.= "  AND categories.category_id=$group ";
				if (@$usluga_id) $query2.= "  AND products.id IN (".implode(",", $usluga_id).")";
				  $query2.=" ORDER BY series_movement.product_id, series_movement.store_id,  series_movement.operation_dt";  
				  
				 $connection =   Yii::app()->db;		
				$command=$connection->createCommand($query2)	;
				$dataReader=$command->query();
				$rows=$dataReader->readAll();		
				return $rows;
		}/////////////public function movement2() {/////// движение по ск
		
public function stores_series(){/////////////////Партии товаров на складах

			if (isset($this->parametrs) AND @count($this->parametrs)>0) {
							foreach($this->parametrs as $parametr_name=>$value):
									($value=='on') ? $$parametr_name=1:$$parametr_name=$value;
									//echo $parametr_name.' = '.$$parametr_name.'<br>';
							endforeach;
			}/////////////if (isset($this->parametrs) AND @count($this->parametrs)>0) {
		
			//print_r($this->parametrs);
			
			$list_from =$this->parametrs[date_from_value];
			$list_upto =$this->parametrs[date_to_value];		

		if (!@$detailed) {
		$query = "SELECT parent_categories.category_name  AS sgr, 
		products.product_name, 
		 SUM(series.num) AS prihod,
		  series_movement_temp.rashod  ,
		 categories.category_name  AS gr,
		 series.nds AS nds_in,
		 series.price_no_nds AS price_no_nds_in";
		  if (@$enable_suplier==1) $query.= ",
		  if( series.original_series  > 0 , co_original.name, contr_agents.name )   AS supname";
		$query.= "  FROM  products 
		  LEFT JOIN series ON series.product_id = products.id 
		  LEFT JOIN 
		  (SELECT SUM( series_movement.num) AS rashod, series_movement.product_id 
		   FROM series_movement
WHERE series_movement.store_id =$store_id ";
if (@$list_from  )  $query.="  AND series_movement.operation_dt>='".Yii::app()->GP->get_sql_date($list_from, "00:00:00")."'  ";
if (@$list_upto  )  $query.="   AND series_movement.operation_dt<='".Yii::app()->GP->get_sql_date($list_upto, "23:59:59")."' ";
$query.= " GROUP BY series_movement.product_id
) series_movement_temp ON series.product_id = series_movement_temp.product_id 
JOIN  categories  ON categories.category_id = products.category_belong 
LEFT JOIN  categories  parent_categories ON categories.parent = parent_categories.category_id

LEFT JOIN documents docs_original ON docs_original.id=series.original_series 
LEFT JOIN contr_agents  co_original ON co_original.id = docs_original.kontragent_id 

LEFT JOIN contr_agents ON contr_agents.id = series.kontragent_id
 
WHERE series.store_id =$store_id";
if (@$sgroup ) $query.= "  AND parent_categories.category_id=$sgroup ";
if (@$group ) $query.= "  AND categories.category_id = $group ";
if (@$list_from  )  $query.="  AND series.arrive_dt >= '".Yii::app()->GP->get_sql_date($list_from, "00:00:00")."'  ";
if (@$list_upto  )  $query.="   AND series.arrive_dt <= '".Yii::app()->GP->get_sql_date($list_upto, "23:59:59")."' ";

$query.= " GROUP BY products.product_name";
//$query.= "   ORDER BY products.product_name, series.arrive_dt";

}////////////if (!@$detailed) {

//echo $query;

if (@$detailed==1) {
		$query = "SELECT series.id, parent_categories.category_name  AS sgr, 
		CONCAT_WS(',', products.product_name , product_attribute.attribute_value) AS product_name,  
		 SUM(series.num) AS prihod,
		 
		 
 if( series_movement_temp.rashod IS NULL , 0, series_movement_temp.rashod) AS rashod, 
		 
		 categories.category_name  AS gr, 
		 series.nds AS nds_in,
		 series.price_no_nds AS price_no_nds_in,
		   series.arrive_dt,
		   series.doc_id ";
		    if (@$enable_suplier==1) $query.= ", 
			if( series.original_series  > 0 , co_original.name, contr_agents.name )   AS supname";
$query.= " FROM series LEFT JOIN 
		  (SELECT SUM( series_movement.num ) AS rashod, 
		  series_movement.product_id,
		   series_id
		    FROM series_movement
WHERE series_movement.store_id =$store_id ";
if (@$list_from  )  $query.="  AND series_movement.operation_dt>='".Yii::app()->GP->get_sql_date($list_from, "00:00:00")."'  ";
if (@$list_upto  )  $query.="   AND series_movement.operation_dt<='".Yii::app()->GP->get_sql_date($list_upto, "23:59:59")."' ";
$query.= " GROUP BY series_movement.product_id, series_id 
) series_movement_temp ON series.id = series_movement_temp.series_id 
JOIN  products ON series.product_id = products.id  
JOIN  categories  ON categories.category_id = products.category_belong  
LEFT JOIN categories parent_categories ON categories.parent = parent_categories.category_id

LEFT JOIN documents docs_original ON docs_original.id=series.original_series 
LEFT JOIN contr_agents  co_original ON co_original.id = docs_original.kontragent_id 
 
LEFT JOIN contr_agents ON contr_agents.id = series.kontragent_id ";

$query.=" LEFT JOIN (SELECT id_product, GROUP_CONCAT( value) AS  attribute_value
FROM `characteristics_values` JOIN (SELECT * FROM products WHERE product_parent_id >0) child_products ON child_products.id = characteristics_values.id_product GROUP BY id_product) product_attribute ON product_attribute.id_product = products.id ";

$query.=" WHERE series.store_id =$store_id ";
if (@$sgroup ) $query.= "  AND parent_categories.category_id=$sgroup ";
if (@$group ) $query.= "  AND categories.category_id=$group ";
if (@$list_from  )  $query.="  AND series.arrive_dt>='".Yii::app()->GP->get_sql_date($list_from, "00:00:00")."'  ";
if (@$list_upto  )  $query.="   AND series.arrive_dt<='".Yii::app()->GP->get_sql_date($list_upto, "23:59:59")."' ";
$query.= " GROUP BY  series.price_no_nds, products.id, products.product_name, series.nds ,  series.arrive_dt ";

  if (@$not_nulls)  $query.= " HAVING  (prihod-rashod) <>0 "; 
//$query.= " ORDER BY products.product_name, series.arrive_dt ";

}
if ($sort_order==6 AND $enable_suplier==1) $query.= " ORDER BY supname ";
if (!@$sort_order==6) $query.= " ORDER BY products.product_name, series.arrive_dt ";



 $connection =   Yii::app()->db;		
$command=$connection->createCommand($query)	;
$dataReader=$command->query();
$rows=$dataReader->readAll();		
return $rows;
		
		}///////////////////public function stores_series(){/////////////////Партии товаров на складах
		
		
	public function stores(){///////////Остатки по всем складам
	
			if (isset($this->parametrs) AND @count($this->parametrs)>0) {
							foreach($this->parametrs as $parametr_name=>$value):
									($value=='on') ? $$parametr_name=1:$$parametr_name=$value;
									//echo $parametr_name.' = '.$$parametr_name.'<br>';
							endforeach;
			}/////////////if (isset($this->parametrs) AND @count($this->parametrs)>0) {
		
			//print_r($this->parametrs);
			
			$list_from =$this->parametrs[date_from_value];
			$list_upto =$this->parametrs[date_to_value];
			if ($goodlist != NULL) $usluga_id = explode('#', $goodlist);
		
		
		$query="SELECT products.id, parent_categories.category_name  AS sgr, 
		 CONCAT_WS(',', products.product_name , product_attribute.attribute_value) AS product_name, 
		   categories.category_name  AS gr ";
		for($k=0;$k<count($stores_id);$k++) {
		$kk=$k+1;
		$query.=", store".$kk.".prihod AS prihod_store".$kk.",  store".$kk.".rashod AS rashod_store".$kk."  ";
		}
		
//		store1.prihod, store1.rashod, 
//		 store2.prihod AS prihod_store2, store2.rashod AS rashod_store2, 
//		 store3.prihod AS prihod_store3, store3.rashod AS rashod_store3
		   $query.="FROM  products
		   JOIN  categories  ON categories.category_id = products.category_belong 
		   LEFT JOIN categories   parent_categories ON categories.parent = parent_categories.category_id 
			 LEFT JOIN  ";
		  for($k=0;$k<count($stores_id);$k++) {
		$kk=$k+1;
		   $query.=" (SELECT parent_categories.category_name  AS sgr, 
		products.product_name, 
		 SUM(series.num) AS prihod,
		  series_movement_temp.rashod  ,
		 categories.category_name  AS gr,
		 products.id 
		  FROM series LEFT JOIN 
		  (SELECT SUM( series_movement.num) AS rashod, series_movement.product_id
FROM series_movement 
WHERE series_movement.store_id =".$stores_id[$k];
if (@$list_from  )  $query.="  AND series.arrive_dt>='".Yii::app()->GP->get_sql_date($list_from, "00:00:00")."'  ";
if (@$list_upto  )  $query.="   AND series.arrive_dt<='".Yii::app()->GP->get_sql_date($list_upto, "23:59:59")."' ";
$query.= " GROUP BY series_movement.product_id
) series_movement_temp ON series.product_id = series_movement_temp.product_id 
JOIN  products ON series.product_id = products.id 
JOIN  categories  ON categories.category_id = products.category_belong 
LEFT JOIN categories parent_categories ON categories.parent = parent_categories.category_id ";


$query.=" WHERE series.store_id =".$stores_id[$k];
if (@$sgroup ) $query.= "  AND parent_categories.category_id=$sgroup ";
if (@$group ) $query.= "  AND categories.category_id=$group ";
if (@$list_from  )  $query.="  AND series.arrive_dt>='".Yii::app()->GP->get_sql_date($list_from, "00:00:00")."'  ";
if (@$list_upto  )  $query.="   AND series.arrive_dt<='".Yii::app()->GP->get_sql_date($list_upto, "23:59:59")."' ";

$query.= " GROUP BY products.id, products.product_name  ORDER BY products.product_name, series.arrive_dt, products.id ";
$query.= "  ) store".$kk;
if ($k>0) $query.= " ON products.id = store$kk.id "; 
if ($k==0) $query.= "  ON products.id = store1.id "; 
if (($k+1)<count($stores_id)) $query.=" LEFT JOIN ";
}//////////  for($k=0;$k<count($stores_id);$k++) {

$query.=" LEFT JOIN (SELECT id_product, GROUP_CONCAT( value) AS  attribute_value
FROM `characteristics_values` JOIN (SELECT * FROM products WHERE product_parent_id >0) child_products ON child_products.id = characteristics_values.id_product GROUP BY id_product) product_attribute ON product_attribute.id_product = products.id ";

$query.= "  WHERE products.id>0 ";
if (@$usluga_id) $query.= "  AND products.id IN (".implode(",", $usluga_id).")";
if (@$sgroup ) $query.= "  AND parent_categories.category_id=$sgroup ";
if (@$group ) $query.= "  AND categories.category_id=$group ";
$query.= "  ORDER BY products.product_name ";
	
		$connection =   Yii::app()->db;		
		$command=$connection->createCommand($query)	;
		$dataReader=$command->query();
		$rows=$dataReader->readAll();		
		return $rows;
	}////////////////////////////////	public function stores(){///////////Остатки по всем складам
		
}////////////class