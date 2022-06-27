<?
//include ("GroupsMenu.js");

class GroupsMenu {
var $menu_levels;
private $show_group;

function __construct(){
$connection = Yii::app()->db;
//$this->show_group = $sg;
if(isset($_GET['show_group']) AND trim($_GET['show_group'])) $this->show_group = $_GET['show_group'];
//else $this->show_group=Yii::app()->GP->GP_sg;
$pd = Yii::app()->getRequest()->getParam('pd', NULL);
if (isset($pd) AND !$this->show_group) {
	$product = Products::model()->findbyPk($pd);
	//$gr = Categoriestradex::model()->findbyPk($product->category_belong);
	//if ($gr->)
	$this->show_group = $product->category_belong;
}///////////////////

$this->menu_levels =  Yii::app()->GP->GP_cat_gl; ////////Количество уровней меню
$query = "SELECT  category_id,  category_name FROM categories WHERE show_category = 1 AND (parent = 0 OR parent = NULL)
ORDER by categories.category_name ";
$command=$connection->createCommand($query)	;
$dataReader=$command->query();
while(($row=$dataReader->read())!==false) {
$this->hi_level_ids[] = $row['category_id'];
$this->hi_level_names[] = $row['category_name'];
$this->ids[0][]   = $row['category_id'];
$this->names[0][] = $row['category_name'];
$this->parentt[0][] = 0;
}
//if (isset($this->ids)) {//////////////Если вообще есть группы
//for ($i=0; $i<count($ids[0]); $i++) {
//echo "$i - ".$ids[0][$i]." - ".$names[0][$i]."<br>";
if (isset($this->ids)) {
for ($level=1; $level < $this->menu_levels;$level++) {
$query = "SELECT  categories.category_id,  categories.category_name, categories.parent, count(child_categories.category_id) as num_of_child FROM categories LEFT JOIN categories child_categories ON categories.category_id = child_categories.parent WHERE categories.show_category = 1 AND categories.parent  IN (".implode(",",$this->ids[($level-1)]).") 
GROUP BY categories.category_id,  categories.category_name, categories.parent
ORDER BY categories.category_name ";
//echo $query.'<br>';
$command=$connection->createCommand($query)	;
$dataReader=$command->query();
while(($row=$dataReader->read())!==false) {
$this->ids[$level][]   = $row['category_id'];
$this->names[$level][] = $row['category_name'];
$this->parentt[$level][] = $row['parent'];
$this->num_of_child[$row['category_id']]=$row['num_of_child'];
//if (in_array($next[2], $ids[$level-1])) $child[$level-1][]=$next[0];
}////////while ($next=mysql_fetch_row($res) ) { 
}///////////for ($level=1; $level<$menu_levels;$level++) {

//print_r($this->ids[1]); группы вложенные в главные


if (isset($this->show_group)) {//////////ищем кто у выбранной директории родители
$level = $this->menu_levels;
/*
echo 'show_group = |'.$this->show_group.'|<br>';
echo 'level = |'.$level.'|<br>';
$level=0;
print_r($this->ids);
*/	
		while (@!in_array($this->show_group, $this->ids[$level] )) {
		$level = $level-1;/////////Уровень меню,  0 - самый верхний
		}///////////while (!in_array($show_group, $ids[$level] ) {
	
	
}/////if ($show_group) {
//echo "$level<br>";

//for ($i=0; $i<count($ids[$level]); $i++) echo $ids[$level][$i]."<br>";

if (isset($this->show_group)) {/////////Определяем ветвь
$current_id = $this->show_group;
$this->parrent_array[]  = $this->show_group;
while ($level>0) {////////////теперь пишем значения парента для выбранной группы в массив
//$parrent_array[] = 
///Находим id массиве $level
$id = array_search ( $current_id, $this->ids[$level] );
$current_parrent = $this->parentt[$level][$id];
$this->parrent_array[] = $current_parrent;
$current_id = $current_parrent;
$level = $level - 1;
}
}////////if (@$show_group) {/////////Определяем ветвь

//for ($i=0; $i<count($parrent_array); $i++) {
//echo $parrent_array[$i]."<br>";
//}
}/////if (isset($this->ids)) {
}///////////////__constuct

function chech_child_categs($gr_id){
//if (array_key_exists($gr_id, $this->num_of_child)) return $this->num_of_child[$gr_id];
//else return NULL;
return $this->num_of_child[$gr_id];
}
/*
function chech_child_categs($gr_id){
$connection = Yii::app()->db;
$query = "SELECT count(category_id) As cat_num FROM categories WHERE parent = $gr_id";
echo $query.'<br>';
$command=$connection->createCommand($query)	;
$dataReader=$command->query();
$def=$dataReader->read();
if ($def) return $def['cat_num'];
else return NULL;
}//////function chech_child_categs($gr_id, $cn){
*/
function print_child1($ids, $names, $parent,  $level, $el_val, $menu_levels,   $parrent_array) {
echo "<table border=\"0\"  cellpadding=\"0\" cellspacing=\"0\">";
if (($level+1)<$menu_levels) {///////
for ($i=0; $i<count(@$ids[$level+1]); $i++) {
$children = $this->chech_child_categs($ids[$level+1][$i]);
if ($parent[$level+1][$i]==$el_val) {
echo "<tr><td valign=top><img src=\"../images/plus.gif\" border=\"0\" id=\"pic_".$ids[$level+1][$i]."\" ";
if ($children) echo "onClick=\"{switch_visibility(".$ids[$level+1][$i].")}\"";
echo "></td><td><a href=\"http://".$_SERVER['HTTP_HOST']."/product/list?show_group=".$ids[$level+1][$i]."\">".$names[$level+1][$i]."</a></td></tr>";
if ($children) {
echo "<tr id=\"group_".$ids[$level+1][$i]."\"";
echo " style=\"display:none\"><td>&nbsp;</td><td>";
$this->print_child2($ids, $names, $parent,  ($level+1), $ids[$level+1][$i], $menu_levels,   $parrent_array);
echo "</td></tr>";
}/////////////if (chech_child_categs($ids[$level+1][$i], $cn)) {
}////////if ($parent[$level+1][$i]==$el_val) {
}//////for($i=0; $<coint($ids[$level+1]); $i++) {
}////////if (($level+1)<$menu_levels) {///////
echo "</table>";
}///////function print_vetv() {

function print_child2($ids, $names, $parent,  $level, $el_val, $menu_levels,  $parrent_array) {
echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
if (($level+1)<$menu_levels) {
for ($i=0; $i<count($ids[$level+1]); $i++) {
$children = $this->chech_child_categs($ids[$level+1][$i]);
if ($parent[$level+1][$i]==$el_val) {
echo "<tr><td>&nbsp;<img src=\"../images/plus.gif\" border=\"0\" id=\"pic_".$ids[$level+1][$i]."\" ";
if ($children) echo "onClick=\"{switch_visibility(".$ids[$level+1][$i].")}\"";
echo "></td><td><a href=\"http://".$_SERVER['HTTP_HOST']."/product/list?show_group=".$ids[$level+1][$i]."\">".$names[$level+1][$i]."</a></td></tr>";
if ($children) {
echo "<tr id=\"group_".$ids[$level+1][$i]."\"";
echo " style=\"display:none\"><td>&nbsp;</td><td>";
$this->print_child1($ids, $names, $parent,  ($level+1), $ids[$level+1][$i], $menu_levels,  $parrent_array);
echo "</td></tr>";
}//////////if (chech_child_categs($ids[$level+1][$i], $cn)) {
}/////if ($parent[$level+1][$i]==$el_val) {
}//////for($i=0; $<coint($ids[$level+1]); $i++) {
}/////if (($level+1)<=$menu_levels) {
echo "</table>";
}///////function print_vetv() {

function Draw() {
if (isset($this->ids)) {
$this->DrawJS();
echo "<table border='0' cellpadding='0' cellspacing='0'>";
//for ($level=0; $level<1; $level++) {
$level = 0;
for ($i=0; $i<count($this->ids[$level]); $i++) {
echo "<tr>";
echo "<td valign='top'>&nbsp;<img src=\"../images/plus.gif\" border=\"0\" id=\"pic_".$this->ids[$level][$i]."\"  onClick=\"{switch_visibility(".$this->ids[$level][$i].")}\"></td><td><a href=\"http://".$_SERVER['HTTP_HOST']."/product/list?show_group=".$this->ids[$level][$i]."\">".$this->names[$level][$i]."</a></td></tr>";
echo "<tr id=\"group_".$this->ids[$level][$i]."\"";
if (@!in_array($this->ids[$level][$i], $this->parrent_array)) echo "style=\"display:none\"";
echo "><td>&nbsp;</td><td>";
$this->print_child1($this->ids, $this->names, $this->parentt,  $level, $this->ids[$level][$i], $this->menu_levels,  @$parrent_array);
echo "</td></tr>";
}//////////for ($i=0; $i<count($ids); $i++) {
//}///////////for (for ($level=0; $level<$menu_levels;$level++) {
echo "</table>";
$this->DrawJS2();
}///////////if (isset($this->ids)) {
}///////////////public function Draw() {


function DrawJS(){
echo "<script>
function switch_visibility( element_id) {
ide='pic_'+element_id;
tbl_id='group_'+element_id;
switched='closed';
if (document.getElementById(tbl_id).style.display !='') {
document.getElementById(tbl_id).style.display='';
document.getElementById(ide).src=\"http://".$_SERVER['HTTP_HOST']."/images/minus.gif\";
switched=\"opened\";
}
if (document.getElementById(tbl_id).style.display !=\"none\" ) { 

if (switched !=\"opened\") {
document.getElementById(tbl_id).style.display=\"none\";
document.getElementById(ide).src=\"http://".$_SERVER['HTTP_HOST']."/images/plus.gif\";
switched=\"closed\";
}
}
}//////////function
</script>";
//include ("GroupsMenu.js");
}///////function DrawJS(){


function DrawJS2(){
if (isset($this->parrent_array) AND isset($this->show_group)) {
echo "
<script>
var item = new Array();".chr(13);
for ($i=0; $i<count($this->parrent_array); $i++) {
echo "item[$i] = ".$this->parrent_array[$i].chr(13);
}
echo "for (i=0; i<".count($this->parrent_array)."; i++) {".chr(13);
echo "qqq = item[i]+\"\";
tbl_id=\"group_\"+qqq;
ide=\"pic_\"+qqq;
if (document.getElementById(tbl_id) != null) document.getElementById(tbl_id).style.display =\"\";
if (document.getElementById(ide) != null) document.getElementById(ide).src=\"http://".$_SERVER['HTTP_HOST']."/images/minus.gif\";
}
</script>";
}
//else echo "<ul><li>группы не заданы</li></ul>";
}

}////////////GroupsMenu class
?>


