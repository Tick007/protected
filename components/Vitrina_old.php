<?
class Vitrina   extends CWidget {
var $menu_levels;
private $show_group;
public $models;

function __construct(){
		
}

function Draw() {

$ProductList=new Product;
$ProductList->product_vitrina = 1;
$ProductList->creteria = " AND parent_categories.show_category = 1";
$ProductList->ExecuteObject(); ////////////перенес из констракта в отдельный метод
$ProductList->offset = 0;
$ProductList->limit = 10;
$models = $ProductList->run_query();		

$this->render('vitrina', array('models'=>$models));		
		


}///////////////public function Draw() {

function DrawSmall() {

$ProductList=new Product;
$ProductList->product_vitrina = 1;
$ProductList->creteria = " AND parent_categories.show_category = 1  ";
$ProductList->orderby = " price_with_nds DESC ";
$ProductList->ExecuteObject(); ////////////перенес из констракта в отдельный метод
$ProductList->offset = 0;
$ProductList->limit = 20; 
$models = $ProductList->run_query();		

$this->render('vitrinasmall', array('models'=>$models));		
}///////////////public function Draw() {


function DrawPsg() {

$ProductList=new Product;
$ProductList->product_vitrina = 1; 
$ProductList->creteria = " AND parent_categories.show_category = 1 ";
$ProductList->ExecuteObject(); ////////////перенес из констракта в отдельный метод
$ProductList->offset = 0;
$ProductList->limit = 10;
$models = $ProductList->run_query();		


$this->render('vitrinapsg', array('models'=>$models));		
		


}///////////////public function Draw() {



}///////////class Vitrina {
?>


