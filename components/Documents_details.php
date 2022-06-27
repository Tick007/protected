<?
class Documents_details {
var $doc_id; /////////////Id 
var $type; 
var $date_dt;
var $kontragent_id;
var $kontragent_name;
var $kontragent_inn;
var $kontragent_kpp;
var $kontragent_contacts;
var $kontragent_ur_adress;
var $store_id_ca;
var $store_ca_name;
var $store_ca_adress;
var $store_id;
var $dogovor_id;
var $doc_type;
var $comments;
var $ext_num;
var $order_id;
var $table_part_id;
var $table_part_product_id;
var $table_part_price_no_nds;
var $table_part_nds;
var $table_part_num;
var $table_part_name;
var $table_part_measure;
var $table_part_code;

var $original_series;////////////////////     
var $our_full_name;
var $our_fiz_adress;
var $our_ur_adress;
var $our_store_name;
var $our_store_adress;
var $our_inn;
var $our_kpp;
var $our_bik;
var $our_rs;
var $our_ks;
var $our_bank; 
var $GP_triggers_enabled;//////////////Переменная показывающая, включены ли триггеры

var $connection;
var $command;
var $dataReader;
var $row;
var $sql_query;
  
private function sql_connect(){
$this->conn_id = mysql_connect('localhost', Yii::app()->db->username, Yii::app()->db->password);
//mysql_select_db('u1424315_bakugan');
mysql_select_db(Yii::app()->params['dbname']);
//mysql_select_db('u1424315_atool');
}/////////////////private function sql_connect(){

private function sql_execute() {
//print_r(Yii::app()->db);
///[connectionString] => mysql:host=localhost;dbname=yii [username] => root [password] => a0806975a [schemaCachingDuration] => 0 [schemaCachingExclude] => Array ( ) [schemaCacheID] => cache [autoConnect] => 1 [charset] => cp125

 $this->sql_res=mysql_query($this->sql_query,$this->conn_id);
   $this->sql_err=mysql_error();
}///////////////private function sql_execute() {

private function start_transaction($cn) {
$trans_query="START TRANSACTION";
$res_trans=mysql_query($trans_query,$cn);
if (!$res_trans) echo "ERROR IN: $trans_query: ".mysql_error()."<br>";
}

function end_transaction($cn) {
$trans_query="COMMIT";
$res_trans=mysql_query($trans_query,$cn);
if (!$res_trans) echo "ERROR IN: $trans_query: ".mysql_error()."<br>";
}

function rolback_transaction($cn) {
$trans_query="ROLLBACK";
$res_trans=mysql_query($trans_query,$cn);
if (!$res_trans) echo "ERROR IN: $trans_query: ".mysql_error()."<br>";
}


function __construct ($doc) {
$this->connection = Yii::app()->db;////////////////Инициализировали связь с YII

//$this->SET_CON_ENC(1);
$this->sql_connect(); ////////////   mysql $conn_id
$this->doc_id=$doc;
$this->sql_query="SELECT  documents.date_dt,   documents.store_id, documents.kontragent_id  , documents.store_id_ca, documents.dogovor_id, documents.doc_type, documents.comments, documents.ext_num, document_types.type, contr_agents.name, stores_ca.name AS store_ca_name,  contr_agents.inn, contr_agents.kpp,  stores_ca.store_adress AS store_ca_adress, contr_agents.ur_adress , order_id
FROM documents JOIN document_types  ON document_types.id = documents.doc_type 
LEFT JOIN contr_agents ON contr_agents.id =  documents.kontragent_id
LEFT JOIN stores stores_ca ON stores_ca.id = documents.store_id_ca
WHERE documents.id = ".$this->doc_id;
$this->sql_execute();
if (!$this->sql_res) echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
$next=mysql_fetch_row($this->sql_res);
//$next = $this->sql_res[0];
//print_r($next);
list ($this->date_dt, $this->store_id, $this->kontragent_id, $this->store_id_ca,  $this->dogovor_id, $this->doc_type, $this->comments, $this->ext_num, $this->type, $this->kontragent_name, $this->store_ca_name,$this->kontragent_inn,$this->kontragent_kpp, $this->store_ca_adress, $this->kontragent_ur_adress, $this->order_id)=$next;
$this->kontragent_name = str_replace("&quot;", "\"", $this->kontragent_name);

$this->table_part();////////////////////////////////////////   
$this->our_requizits();/////// 
$this->get_global_parametrs();
}//////////function __construct

function documents_details ($doc) {///////////// constract   PHP4
$this->__construct ($doc);
}//////////function __construct

function get_global_parametrs() {
$this->sql_query="SELECT triggers_enabled FROM general_settings  WHERE setting_id=1";
$this->sql_execute();
if (!$this->sql_res) echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
$next=mysql_fetch_row($this->sql_res);
list ($this->GP_triggers_enabled)=$next;	
}///////////////function get_global_parametrs () {

function table_part(){
$this->sql_query="SELECT 
	document_table_part.id, 
	document_table_part.product_id, 
	document_table_part.price_no_nds, 
	document_table_part.nds, 
	document_table_part.num,
 	products.product_name,
	measures.measure, 
	products.product_article
FROM document_table_part LEFT JOIN  products ON products.id=document_table_part.product_id
LEFT JOIN measures ON measures.id = products.measure 
WHERE document_table_part.doc_id = ".$this->doc_id;
//echo $this->sql_query;

$this->sql_execute();
if (!$this->sql_res) echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
///////////////////////////////////////////////////////////////////////////////////////////////////
$this->table_part_id=NULL;
$this->table_part_product_id=NULL;
$this->table_part_price_no_nds=NULL;
$this->table_part_nds=NULL;
$this->table_part_num=NULL;
$this->table_part_name=NULL;
$this->table_part_measure=NULL;
$this->table_part_code=NULL;
while ($next=mysql_fetch_row($this->sql_res) ) {
$this->table_part_id[]=$next[0];
$this->table_part_product_id[]=$next[1];
$this->table_part_price_no_nds[]=$next[2];
$this->table_part_nds[]=$next[3];
$this->table_part_num[]=$next[4];
$this->table_part_name[]=$next[5];
$this->table_part_measure[]=$next[6];
$this->table_part_code[]=$next[7];
}///////////////while ($next=mysql_fetch_row($this->sql_res) ) {
}///////////function table_part(){

function our_requizits(){
$this->sql_connect(); ////////////   mysql $conn_id

$this->sql_query="SELECT   contr_agents.full_name AS our_full_name, stores.name AS our_strore_name,  contr_agents.inn, contr_agents.kpp,  stores.store_adress AS our_store_adress, contr_agents.ur_adress AS our_ur_adress,  contr_agents.ur_adress AS our_fiz_adress , contr_agents.contacts, contr_agents.r_s,  contr_agents.bank,  contr_agents.bik,  contr_agents.k_s  
FROM documents JOIN document_types  ON document_types.id = documents.doc_type 
LEFT JOIN stores  ON stores.id = documents.store_id
LEFT JOIN contr_agents ON stores.kontragent_id =  contr_agents.id 
WHERE contr_agents.id =  documents.id = ".$this->doc_id;

$this->sql_execute();
if (!$this->sql_res) echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
$next=mysql_fetch_row($this->sql_res);
list ($this->our_full_name, $this->our_strore_name, $this->our_inn,$this->our_kpp,$this->our_store_adress,$this->our_ur_adress,$this->our_fiz_adress, $this->kontragent_contacts, $this->our_rs,  $this->our_bank,  $this->our_bik,  $this->our_ks)=$next;
$this->our_full_name = str_replace("&quot;", "\"", $this->our_full_name);

}

function our_requizits_short(){
$this->sql_connect(); ////////////   mysql $conn_id

$this->sql_query="SELECT contr_agents.full_name AS our_full_name, contr_agents.inn, contr_agents.kpp, contr_agents.ur_adress AS our_ur_adress, contr_agents.ur_adress AS our_fiz_adress, contr_agents.contacts, contr_agents.r_s, contr_agents.bank, contr_agents.bik, contr_agents.k_s
FROM contr_agents
WHERE contr_agents.id = ( 
SELECT self_contragent
FROM general_settings
WHERE setting_id =1 ) ";

$this->sql_execute();
if (!$this->sql_res) echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
$next=mysql_fetch_row($this->sql_res);
list ($this->our_full_name,  $this->our_inn,$this->our_kpp,$this->our_ur_adress,$this->our_fiz_adress, $this->kontragent_contacts, $this->our_rs,  $this->our_bank,  $this->our_bik,  $this->our_ks)=$next;
$this->our_full_name = str_replace("&quot;", "\"", $this->our_full_name);

}

function ReadAttr($attribute) {
echo $this->$attribute;
}

function WriteAttr($attribute, $value) {
$this->$attribute= $value;
}

function GetAttrValue($attribute) {
return $this->$attribute;
}


function SaveAttributes() {
if (@!$this->kontragent_id) $this->kontragent_id=0;
if (@!$this->store_id) $this->store_id=0;
if (@!$this->store_id_ca) $this->store_id_ca=0;
$this->sql_query="UPDATE documents  SET kontragent_id = ".$this->kontragent_id.", store_id= ".$this->store_id." , store_id_ca= ".$this->store_id_ca.",   
date_dt='".$this->date_dt."' ";
if (isset($this->order_id))$this->sql_query.=", order_id=".$this->order_id;
$this->sql_query.=", ext_num='".$this->ext_num."'"; 
$this->sql_query.=", comments='".$this->comments."'"; 
$this->sql_query.=" WHERE (id=".$this->doc_id.");";
$this->sql_execute();
if (!$this->sql_res) echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
}

function AddTablePart($usluga_id) {
$this->table_part_product_id[]=$usluga_id;
$this->table_part_id[]=NULL;
$this->table_part_price_no_nds[]=0;
$this->table_part_nds[]='0.18';
$this->table_part_num[]=1;
}//////////////////function AddTablePart($usluga_id) {

function AddTablePartPriceNum($usluga_id, $nds, $price, $num) {
$this->table_part_product_id[]=$usluga_id;
$this->table_part_id[]=NULL;
$this->table_part_price_no_nds[]=$price;
$this->table_part_nds[]=$nds;
$this->table_part_num[]=$num;
}//////////////////function AddTablePart($usluga_id) {

function SaveNewTablePart() {
for($i=0; $i<count($this->table_part_id); $i++) {
//echo "$this->table_part_id[$i]";
if ($this->table_part_id[$i]==NULL) {
$this->sql_query="INSERT INTO document_table_part  (doc_id, product_id, price_no_nds, nds, num)  VALUES (".$this->doc_id.", ".$this->table_part_product_id[$i].",'".$this->table_part_price_no_nds[$i]."', '".$this->table_part_nds[$i]."', '".$this->table_part_num[$i]."' )";
$this->sql_execute();
if (!$this->sql_res) echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
$this->sql_query="SELECT LAST_INSERT_ID()";
$this->sql_execute();
if (!$this->sql_res) echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
$def=mysql_fetch_row($this->sql_res);
$rec_id = $def[0];
$this->table_part_id[$i]=$rec_id;

}//////////////////if ($this->table_part_id=NULL) {
}//////////////////for($i=0; $i<count($this->table_part_id); $i++) {
}/////////////function SaveNewTablePart() {

function ReinicialiseAfterCopy($correct_doc_id) {
$this->doc_id=$correct_doc_id;
$this->date_dt = date("Y-m-d H:i:s");
}

function SaveCopiedTableParts() {//////////      1 
for($i=0; $i<count($this->table_part_id); $i++) {
$this->sql_query="INSERT INTO document_table_part  (doc_id, product_id, price_no_nds, nds, num)  VALUES (".$this->doc_id.", ".$this->table_part_product_id[$i].",  '".$this->table_part_price_no_nds[$i]."', 
 '".$this->table_part_nds[$i]."', '".$this->table_part_num[$i]."')";
$this->sql_execute();
if (!$this->sql_res) echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
}///////////////for($i=0; $i<count($this->table_part_id); $i++) {
}////////////////function SaveCopiedTableParts() {//////////      1 


function ProvedeniePrihod(){
if ($this->CheckProvedenie_status()==NULL OR $this->CheckProvedenie_status()==0) {
//////////////////////////////////
$this->sql_query="SELECT 
	document_table_part.id, 
	document_table_part.product_id, 
	document_table_part.price_no_nds, 
	document_table_part.nds, 
	document_table_part.num, 
	products.product_name, 
	products.measure,
	products.product_price 
FROM document_table_part
JOIN  products ON products.id = document_table_part.product_id
WHERE document_table_part.doc_id =".$this->doc_id;
//echo $this->sql_query."<br>";
$this->sql_execute();
if (!$this->sql_res) echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
$i=0;
while ($next=mysql_fetch_row($this->sql_res)) {
$query1="INSERT INTO series (product_id, arrive_dt, num, store_id, price_no_nds, nds, doc_id, kontragent_id, store_id_ca)  
VALUES ( $next[1],  '".$this->date_dt."', $next[4], ".$this->store_id.", $next[2], $next[3], ".$this->doc_id." , ".$this->kontragent_id.", ".$this->store_id_ca." )";
//echo $query1."<br>";
$res1=mysql_query($query1,$this->conn_id);
if (@!$res1) $error.= "ERROR IN: $query1: ".mysql_error()."<br>";
if (@!$this->GP_triggers_enabled) $this->emulate_triggers_ostatki($next[1], $this->store_id);
}//////////while ($next=mysql_fetch_row($this->sql_res)) {
}///////////////////////if ($this->CheckProvedenie_status()==NULL OR $this->CheckProvedenie_status()==0) {
return $error;
}/////////function ProvedeniePrihod(

function CheckProvedenie_status() {
if ($this->doc_type==1 OR $this->doc_type==4) {////////////////   
$this->sql_query="SELECT count( * )  FROM `series` WHERE `doc_id` =".$this->doc_id;
//echo $this->sql_query."<br>";
$this->sql_execute();
if (!$this->sql_res) echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
$def= mysql_fetch_row($this->sql_res);
return $def[0]; ////////////      -      0
}/////if ($this->doc_type==1) {///////
else if ($this->doc_type==2 OR $this->doc_type==3) {/////////
$this->sql_query="SELECT count( * )  FROM `series_movement`  WHERE `doc_id` = ".$this->doc_id;
//echo $this->sql_query."<br>";
$this->sql_execute();
if (!$this->sql_res) echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
$def= mysql_fetch_row($this->sql_res);
return $def[0]; ////////////      -      0
}//////////else if ($this->doc_type==2) {/////////
}/////////////function CheckProvedenie_status {

function check_aviability(){////////  
$cn=$this->conn_id;
$this->sql_query="SELECT 
	document_table_part.id, 
	document_table_part.product_id, 
	document_table_part.price_no_nds, 
	document_table_part.nds, 
	document_table_part.num, 
	products.product_name, 
	products.measure,
	products.product_price 
FROM document_table_part
JOIN  products ON products.id = document_table_part.product_id
WHERE document_table_part.doc_id =".$this->doc_id;
$this->sql_execute();
if (!$this->sql_res) echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
//echo $query;
while ($next=mysql_fetch_row($this->sql_res)) {//////  
//echo "$next[1] - $next[4]<br>";
//$a=$this->provesti_series_for_sell($next[1], $next[4], $this->conn_id, $this->doc_id, $this->store_id, $this->store_id_ca,  $this->kontragent_id,  $next[2], //$next[3] );
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$product_id=$next[1];
$store_doc=$this->store_id;
$num=$next[4];
$query1  = "SELECT series.id, series.num, SUM( series_movement.num ) AS rashod, if( (
series.num - SUM( series_movement.num ) ) IS NULL , series.num, (
series.num - SUM( series_movement.num ) 
)
) AS for_sell, series.price_no_nds, series.nds, series.original_series
FROM series_movement
RIGHT JOIN series ON series_movement.series_id = series.id
WHERE series.product_id =$product_id AND series.store_id = $store_doc 
GROUP BY series.id, series.num, series.price_no_nds, series.nds, original_series
HAVING for_sell >0
OR for_sell IS NULL 
ORDER BY series.arrive_dt, series.id";
//echo "$query1<br>";
$res1=mysql_query($query1,$cn);
if (!$res1) echo "ERROR IN: $query1: ".mysql_error()."<br>";
$sum_avialable=0;
while ($next1=mysql_fetch_row($res1)) {///
$series_to_decrease_price[] = $next1[4];
$series_to_decrease_nds[] = $next1[5];
$sum_avialable = $sum_avialable+$next1[3];
if ($num >= $sum_avialable) {
//echo "- $sum_avialable-";
$series_to_decrease[]=$next1[0];
$series_to_decrease_num[]=$next1[3];
}
else {
//echo "- ".($num-($sum_avialable-$next1[3]));
$series_to_decrease[]=$next1[0];
$series_to_decrease_num[]=$num-($sum_avialable-$next1[3]);
}
//echo "<br>";
}/////////////while ($next1=mysql_fetch_row($res1)) {///

if ($sum_avialable < $num) {///////////..      
$unavialable_array[0][]=$product_id;
$unavialable_array[1][]=$sum_avialable;
}///////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
}///////////////////while ($next=mysql_fetch_row($res)) {///  
if (isset($unavialable_array)) return $unavialable_array;
}///////////function check_aviability(){////////  

function ProvedenieRashod_Trasfer() {
if ($this->CheckProvedenie_status()==NULL OR $this->CheckProvedenie_status()==0) {
$not_avialable_array=$this->check_aviability();
if ($not_avialable_array[0]) for ($i=0; $i<count($not_avialable_array[0]); $i++) $errors.="<div class=plain><font color=#FF0000>Док.№".$this->doc_id." ".Yii::app()->GP->getproductname($not_avialable_array[0][$i])." : ".$not_avialable_array[1][$i]."</font></div><br>";
else {
$this->sql_query="SELECT 
	document_table_part.id, 
	document_table_part.product_id, 
	document_table_part.price_no_nds, 
	document_table_part.nds, 
	document_table_part.num, 
	products.product_name, 
	products.measure,
	products.product_price 
FROM document_table_part
JOIN  products ON products.id = document_table_part.product_id
WHERE document_table_part.doc_id =".$this->doc_id;
$this->sql_execute();
if (!$this->sql_res) echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
//echo $this->sql_query." fff ".$this->sql_res;
while ($next=mysql_fetch_row($this->sql_res)) {//////  
//echo "$next[1] - $next[4]<br>";
//echo 'Цена = '.$next[2].'<br>';
$a=$this->provesti_series_for_sell($next[1], $next[4], $this->conn_id, $this->doc_id, $this->store_id, $this->store_id_ca,  $this->kontragent_id,  $next[2], $next[3] );
}///////////////////while ($next=mysql_fetch_row($res)) {/// 

}/////////////else {if ($not_avialable_array) fo


if (@!$this->GP_triggers_enabled) {
//$this->emulate_triggers_ostatki($product_id, $store_doc);
for ($i=0; $i<count($this->table_part_product_id); $i++) {
$this->emulate_triggers_ostatki($this->table_part_product_id[$i], $this->store_id);
$this->emulate_triggers_ostatki($this->table_part_product_id[$i], $this->store_id_ca);
}/////////for ($i=0; $i<count($this->table_part_product_id); $i++) {
}///////////////if (@!$this->GP_triggers_enabled) {

}//////////if ($this->CheckProvedenie_status()==NULL OR $this->CheckProvedenie_status()==1) {

return $errors;

}////////////function ProvedenieRashod_Trasfer(){


function ProvedenieProizvodstvo($cn) {
if (($this->CheckProvedenie_status()==NULL OR $this->CheckProvedenie_status()==0) AND count($this->table_part_product_id)) {
$this->sql_query="SELECT 
	document_table_part.id, 
	document_table_part.product_id, 
	document_table_part.price_no_nds, 
	document_table_part.nds, 
	document_table_part.num, 
	products.product_name, 
	products.measure,
	products.product_price 
FROM document_table_part
JOIN ".products($cn,"m.p")." products ON products.product_id = document_table_part.product_id
WHERE document_table_part.doc_id =".$this->doc_id;

$this->sql_execute();
if (!$this->sql_res) echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
//echo $query;
while ($next=mysql_fetch_row($this->sql_res)) {//////  
//echo "$next[1] - $next[4] - $next[5]<br>";

$query1="SELECT product_id, product_name, id, recommendations.amount  FROM ".products($cn,"m.p")." products, recommendations WHERE  products.product_id=recommendations.id_recom AND 
		  recommendations.id_product = $next[1]";
		  if (!$res1=mysql_query($query1, $cn)) {
			echo "ERROR:   $query<br>";
			echo mysql_error()."<br>";
			}
			while($next1=mysql_fetch_row($res1))  {/////// 
			//echo "-----$next1[1] - ".$next1[3]*$next[4]."<br>";
			$num_to_spisat = $next1[3]*$next[4];
			$sebestoimost=$this->provesti_series_for_sell($next1[0], $num_to_spisat, $this->conn_id, $this->doc_id, $this->store_id, $this->store_id_ca,  $this->kontragent_id,  $next[2], $next[3] );
			$sebest_sht = $sebestoimost/$next[4];
			//echo "sebestoimost = $sebestoimost - $sebest_sht<br>";
			$sebestoimost_sum = @$sebestoimost_sum+$sebestoimost;
			$sebestoimost_sum_sht = round($sebestoimost_sum/$next[4],3);
			}//////while($next1=mysql_fetch_row($res1))  {/////// 
			//echo "sebestoimost_sum = $sebestoimost_sum - $sebestoimost_sum_sht<br>";
/////////////////    			
if (@$sebestoimost) {
$query1="INSERT INTO series (product_id, arrive_dt, num, store_id, price_no_nds, nds, doc_id, kontragent_id, store_id_ca)  
VALUES ( $next[1],  '".$this->date_dt."', $next[4], ".$this->store_id.", $sebestoimost_sum_sht, '0', ".$this->doc_id." , ".$this->kontragent_id.", ".$this->store_id_ca." )";
$res1=mysql_query($query1,$this->conn_id);

$query2="UPDATE document_table_part SET price_no_nds = '$sebestoimost_sum_sht', nds='0' WHERE id = $next[0]";
$res2=mysql_query($query2,$this->conn_id);
if (@!$res2) echo "ERROR IN: $query2: ".mysql_error()."<br>";
}///////////if (@$sebestoimost_sum_sht) {

}///////////////////while ($next=mysql_fetch_row($res)) {///  


if (@!$this->GP_triggers_enabled) {
//$this->emulate_triggers_ostatki($product_id, $store_doc);
for ($i=0; $i<count($this->table_part_product_id); $i++) {
$this->emulate_triggers_ostatki($this->table_part_product_id[$i], $this->store_id);
//$this->emulate_triggers_ostatki($this->table_part_product_id[$i], $this->store_id_ca);
}
if ($this->doc_type=4) {//if tr1/////////Получавем список товаров для пересчета по производству
$query1="SELECT id_recom FROM recommendations  WHERE id_product IN (".implode(",",$this->table_part_product_id).")";
		  if (!$res1=mysql_query($query1, $this->conn_id)) {
			echo "ERROR:   $query1<br>";
			echo mysql_error()."<br>";
			}
			while($next1=mysql_fetch_row($res1)) {
			//$this->emulate_triggers_ostatki($next1[0], $this->store_id);
			$this->emulate_triggers_ostatki($next1[0], $this->store_id_ca);
			}
}//////////////////if ($this->doc_type=4) {//if tr1/////////Пол
}//////if (@!$this->GP_triggers_enabled) {

}//////////////if ($this->CheckProvedenie_status()==NULL OR $this->CheckProvedenie_status()==0) {
}////////////function ProvedenieProizvodstvo() {


function CancelProvedenie() {
if ($this->CheckProvedenie_status() OR $this->CheckProvedenie_status()>0) {
//$doc_type = get_doc_type($doc_id, $cn);
$this->start_transaction($this->conn_id);
if ($this->doc_type==2 OR $this->doc_type==3 OR $this->doc_type==4) {
///////////////// For MyIsam tables that not support foregn keys
$this->sql_query=" SELECT id FROM series_movement WHERE series_id IN (SELECT id FROM series WHERE doc_id =  ".$this->doc_id.")";
$this->sql_execute();
if (!$this->sql_res) echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
$podchinen=mysql_num_rows($this->sql_res);
if ($podchinen==0) {
//////////////////////
$this->sql_query="DELETE FROM series_movement  WHERE doc_id = ".$this->doc_id;
$this->sql_execute();
if (!$this->sql_res){
$err=mysql_error();
if(strstr($err, 'FOREIGN KEY')) $errors.="Document ".$this->doc_id." can not be cancelled, movement present<br>";
else echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
}//////////if (!$this->sql_res){
}/////////////if ($podchinen==0) {
//else echo "Document ".$this->doc_id." can not be cancelled, movement present (1)<br>";
}/////////////////////////////////if ($this->doc_type==2 OR $this->doc_type==3 OR $this->doc_type==4) {
if ($this->doc_type==1 OR $this->doc_type==3 OR $this->doc_type==4) {
////////////////
///////////////// For MyIsam tables that not support foregn keys
$this->sql_query=" SELECT id FROM series_movement WHERE series_id IN (SELECT id FROM series WHERE doc_id =  ".$this->doc_id.")";
$this->sql_execute();
if (!$this->sql_res) echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
//while ($next=mysql_fetch_row($this->sql_res)) {///
//echo $this->doc_id.": $next[0]<br>";
//}////////////while ($next1=mysql_fetch_row($res1)) {///
//echo $this->doc_id." ".mysql_num_rows($this->sql_res)."<br>";
$podchinen=mysql_num_rows($this->sql_res);
if ($podchinen==0) {
/////////////////////////////////////////////////////////////////////////
$this->sql_query=" DELETE FROM series WHERE doc_id = ".$this->doc_id;
$this->sql_execute();
if (!$this->sql_res) {
$err=mysql_error();
if(strstr($err, 'FOREIGN KEY')) $errors.="Document ".$this->doc_id." can not be cancelled, movement present<br>";
else echo "ERROR IN: ".$this->sql_query.": ".mysql_error()."<br>";
$this->rolback_transaction($this->conn_id);
}/////////////////if (!$this->sql_res) {
}//////////if ($podchinen==0) {
else { //3
$errors.="Document ".$this->doc_id." can not be cancelled, movement present <br>";
$this->sql_query=" SELECT doc_id FROM series_movement WHERE series_id IN (SELECT id FROM series WHERE doc_id =  ".$this->doc_id.") 
GROUP BY doc_id";
$this->sql_execute();
while ($next=mysql_fetch_row($this->sql_res)) {///
$errors.="<a href=\"http://".$_SERVER['HTTP_HOST']."/admindocs/doc/$next[0]\">$next[0]</a><br>";
}////////////while ($next1=mysql_fetch_row($res1)) {///

$this->sql_execute();

}/////////////////else { //3
}///////////////////////////////////////////
$this->end_transaction($this->conn_id);

if (@!$this->GP_triggers_enabled) {
//$this->emulate_triggers_ostatki($product_id, $store_doc);
for ($i=0; $i<count($this->table_part_product_id); $i++) {
$this->emulate_triggers_ostatki($this->table_part_product_id[$i], $this->store_id);
$this->emulate_triggers_ostatki($this->table_part_product_id[$i], $this->store_id_ca);
}
if ($this->doc_type=4) {//if tr1/////////Получавем список товаров для пересчета по производству
$query1="SELECT id_recom FROM recommendations  WHERE id_product IN (".implode(",",$this->table_part_product_id).")";
		  if (!$res1=mysql_query($query1, $this->conn_id)) {
			echo "ERROR:   $query1<br>";
			echo mysql_error()."<br>";
			}
			while($next1=mysql_fetch_row($res1)) {
			$this->emulate_triggers_ostatki($next1[0], $this->store_id);
			$this->emulate_triggers_ostatki($next1[0], $this->store_id_ca);
			}
}//////////////////if ($this->doc_type=1) {//if tr1/////////Пол

}//////////if (@!$this->GP_triggers_enabled) {

}///////////////if ($this->CheckProvedenie_status() OR $this->CheckProvedenie_status()==1) {
return $errors;
}//////////function CancelProvedenie() {

function provesti_series_for_sell($product_id, $num, $cn, $doc_id, $store_doc, $store_doc_ca, $kontragent_id ,  $price_no_nds_out, $nds_out  ) {
//$doc_type = get_doc_type($doc_id, $cn);
$doc_type = $this->doc_type;

$query="LOCK TABLE series_movement WRITE, series WRITE";/////////////////// 
$res=mysql_query($query,$cn);
if (!$res) echo "ERROR IN: $query: ".mysql_error()."<br>";

////////////////////////////////////////////////////////////////////////////////////////////////////////// 
//Beginning a transaction (for example, with START TRANSACTION) implicitly performs an UNLOCK TABLES. (Additional information about the interaction //between table locking and transactions is given later in the section.) 
$query  = "SELECT series.id, series.num, SUM( series_movement.num ) AS rashod, if( (
series.num - SUM( series_movement.num ) ) IS NULL , series.num, (
series.num - SUM( series_movement.num ) 
)
) AS for_sell, series.price_no_nds, series.nds, series.original_series
FROM series_movement
RIGHT JOIN series ON series_movement.series_id = series.id
WHERE series.product_id =$product_id AND series.store_id = $store_doc 
GROUP BY series.id, series.num, series.price_no_nds, series.nds, original_series
HAVING for_sell >0
OR for_sell IS NULL 
ORDER BY series.arrive_dt, series.id";
//echo "$query<br>";
$res=mysql_query($query,$cn);
if (!$res) echo "ERROR IN: $query: ".mysql_error()."<br>";
//echo $query;
$sum_avialable=0;
//$series_to_decrease=NULL;
//$series_to_decrease_num=NULL;
while ($next=mysql_fetch_row($res)) {///
$series_to_decrease_price[] = $next[4];
$series_to_decrease_nds[] = $next[5];
$sum_avialable = $sum_avialable+$next[3];
$original_series[]=$next[6];
//echo "$next[0] - $next[3]<br>";
//echo "sum_avialable = $sum_avialable; num = $num<br>";
if ($num >= $sum_avialable) {
$series_to_decrease[]=$next[0];
$series_to_decrease_num[]=$next[3];
}
else {
//echo "- ".($num-($sum_avialable-$next[3]))."<br>";
$series_to_decrease[]=$next[0];
$series_to_decrease_num[]=$num-($sum_avialable-$next[3]);
}
//echo "<br>";
}/////////////while ($next=mysql_fetch_row($res)) {///

//if ($num  >$sum_avialable)  {////////////если количество недостаточно по сумме партий чем количество товара к списанию

//}//if ($num  >$sum_avialable)  {////////////если количество недостаточно по сумме партий чем количество товара к списанию
//echo "sum_avialable = $sum_avialable; num = $num<br>";
if ($sum_avialable >= $num) {/////////////.. 
$date_dt=date("Y-m-d H:i:s");
$this->start_transaction($cn);

$i=0;
while (@$series_to_decrease_num[$i]>0){
if($original_series[$i]==0) $os=$series_to_decrease[$i];
else if($original_series[$i] > 0) $os=$original_series[$i];
if ($doc_type==2) $query1 = "INSERT INTO series_movement (series_id,  operation_dt,  num,  doc_id,  store_id,  store_id_ca,  price_no_nds_in,  nds_in,  product_id,  kontragent_id,  price_no_nds_out,  nds_out, original_series )  
VALUES  ($series_to_decrease[$i],  '$date_dt',  '$series_to_decrease_num[$i]', $doc_id, $store_doc, $store_doc_ca, $series_to_decrease_price[$i],  $series_to_decrease_nds[$i], $product_id, $kontragent_id,   $price_no_nds_out, $nds_out, $os   )";
/*
else if ($doc_type==3 OR $doc_type==4) $query1 = "INSERT INTO series_movement (series_id,  operation_dt,  num,  doc_id,  store_id,  store_id_ca,  price_no_nds_in,  nds_in,  product_id,  kontragent_id,  price_no_nds_out,  nds_out , original_series)  
VALUES  ($series_to_decrease[$i],  '$date_dt',  '$series_to_decrease_num[$i]', $doc_id, $store_doc, $store_doc_ca, $series_to_decrease_price[$i],  $series_to_decrease_nds[$i], $product_id, $kontragent_id,   $series_to_decrease_price[$i], $series_to_decrease_nds[$i] , $os  )";
*/
else if ($doc_type==3 OR $doc_type==4) {
if ($price_no_nds_out>0 AND is_float($price_no_nds_out))  $series_to_decrease_price[$i] = $price_no_nds_out;
if ($nds_out>0 AND is_float($nds_out))  $series_to_decrease_nds[$i] = $nds_out;
$query1 = "INSERT INTO series_movement (series_id,  operation_dt,  num,  doc_id,  store_id,  store_id_ca,  price_no_nds_in,  nds_in,  product_id,  kontragent_id,  price_no_nds_out,  nds_out , original_series)  
VALUES  ($series_to_decrease[$i],  '$date_dt',  '$series_to_decrease_num[$i]', $doc_id, $store_doc, $store_doc_ca, $series_to_decrease_price[$i],  $series_to_decrease_nds[$i], $product_id, $kontragent_id,   $series_to_decrease_price[$i], $series_to_decrease_nds[$i] , $os  )";
}
//////////////////////   
//$this->table_part_id;
////////////////////////////////
//echo "$query1<br>";
$res1=mysql_query($query1,$cn);
if (@!$res1) echo "ERROR IN: $query1: ".mysql_error()."<br>";

 if ($doc_type==3) {////////////////////        
/*
$query1="INSERT INTO series (product_id, arrive_dt, num, store_id, price_no_nds, nds, doc_id, kontragent_id, store_id_ca, original_series )  
VALUES ( $product_id,  '$date_dt', '$series_to_decrease_num[$i]', $store_doc_ca, $series_to_decrease_price[$i], $series_to_decrease_nds[$i], $doc_id , $kontragent_id, $store_doc , $os)";
*/
if ($price_no_nds_out>0 AND is_float($price_no_nds_out))  $series_to_decrease_price[$i] = $price_no_nds_out;
if ($nds_out>0 AND is_float($nds_out))  $series_to_decrease_nds[$i] = $nds_out;
$query1="INSERT INTO series (product_id, arrive_dt, num, store_id, price_no_nds, nds, doc_id, kontragent_id, store_id_ca, original_series )  
VALUES ( $product_id,  '$date_dt', '$series_to_decrease_num[$i]', $store_doc_ca, $series_to_decrease_price[$i], $series_to_decrease_nds[$i], $doc_id , $kontragent_id, $store_doc , $os)";
$res1=mysql_query($query1,$cn);
if (@!$res1) echo "ERROR IN: $query1: ".mysql_error()."<br>";
$query5="SELECT LAST_INSERT_ID()";
if (!$res5=mysql_query($query5, $cn)) echo mysql_error();
$def5=mysql_fetch_row($res5);
//echo "ins ser_id = ".$def5[0]."<br>"; 
$last_inserted_series_id[] = $def5[0];
}// if ($doc_type==3)//////////////////////////////////////////////////////////////////////////////////////////

$sebestoimost_by_ser=$series_to_decrease_num[$i]*$series_to_decrease_price[$i];
$sebestoimost_by_ser_sum=@$sebestoimost_by_ser_sum+$sebestoimost_by_ser;
$i++;
}////////while////for ($i=0; $i<sizeof($series_to_decrease);  $i++){

$this->end_transaction($cn);


}//////////////if array_sum($series_to_decrease_num>=$num) {/////////////..         
else {////////////////// 
$query2="UNLOCK TABLES";////////// 
$res2=mysql_query($query2,$cn);
if (@!$res2) echo "ERROR IN: $query2: ".mysql_error()."<br>";

echo "<div class=plain><font color=#FF0000>Док.№$doc_id: ".getproductname($product_id,$cn)." : ".$sum_avialable."</font></div><br>";
$query2=" DELETE FROM series_movement  WHERE doc_id = ".$doc_id;
$res2=mysql_query($query2,$cn);
if (@!$res2) echo "ERROR IN: $query2: ".mysql_error()."<br>";
//////         
$query2=" DELETE FROM series  WHERE doc_id = ".$doc_id;
$res2=mysql_query($query2,$cn);
if (@!$res2) echo "ERROR IN: $query2: ".mysql_error()."<br>";
//echo "$query2<br>";
///////           product_id
if (@$last_inserted_series_id) {////////////       product_id
$query2=" DELETE FROM series  WHERE id IN ( ".implode(",", $last_inserted_series_id).")";
$res2=mysql_query($query2,$cn);
//echo "$query2";
if (@!$res2) echo "ERROR IN: $query2: ".mysql_error()."<br>";
												}
}////////////else {

 if ($doc_type==4 AND ($sum_avialable >= $num)) return $sebestoimost_by_ser_sum;

}////////////function show_series_for_sell($product_id, $num, $cn) {

function SET_NAMES($enc_type) {
$this->SET_CON_ENC($enc_type);
$this->sql_connect();
$this->__construct($this->doc_id);
$this->table_part();
}///function SET_NAMES($enc_type) {

function emulate_triggers_ostatki($tovar, $sklad) {/////////Пересчет и запись остатков
$query = "SELECT (IF (store1.prihod IS NULL, 0,store1.prihod)  - IF(store1.rashod IS NULL, 0, store1.rashod) ) AS prod_quant
FROM products
LEFT JOIN (
SELECT SUM( series.num ) AS prihod, series_movement_temp.rashod, products.id AS product_id 
FROM series
LEFT JOIN (
SELECT SUM( series_movement.num ) AS rashod, series_movement.product_id
FROM series_movement
WHERE series_movement.store_id =$sklad
GROUP BY series_movement.product_id
)series_movement_temp ON series.product_id = series_movement_temp.product_id
JOIN products ON series.product_id = products.id
JOIN categories categories ON categories.category_id = products.category_belong
JOIN categories parent_categories ON categories.parent = parent_categories.category_id
WHERE series.store_id =$sklad
AND products.id = $tovar
GROUP BY products.product_name
ORDER BY products.product_name, series.arrive_dt, products.id
)store1 ON products.id = store1.product_id 
WHERE products.id = $tovar ";
$res=mysql_query($query, $this->conn_id);
if (!$res) echo "ERROR IN: ".$query.": ".mysql_error()."<br>";
$def=mysql_fetch_row($res);
//$this->tr_quantity =$def[0];

$query = "DELETE FROM ostatki_trigers WHERE tovar = $tovar AND store = $sklad";
$res=mysql_query($query, $this->conn_id);
if (!$res) echo "ERROR IN: ".$query.": ".mysql_error()."<br>";

$query = "INSERT INTO ostatki_trigers  Set tovar = $tovar, store = $sklad,   quantity  = '$def[0]' ";
$res=mysql_query($query, $this->conn_id);
if (!$res) echo "ERROR IN: ".$query.": ".mysql_error()."<br>";

}///////////////function emulate_triggers_ostatki($tovar, $sklad) {/////////Пересчет и запись остатков


}////////class contragents_details  {

?>