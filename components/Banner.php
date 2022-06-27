<?
class Banner  extends CWidget{
//private $prod_char_id = 175;
function __construct(){
		
}



public function DrawBigBanners() {
		$banners=array('3_1_big.png'=>'1', '3_2_big.png'=>'2', '3_3_big.png'=>'3');
		$this->render('bigbanners', array('banners'=>$banners));
		
}///////////////public function Draw() {



}///////////class Vitrina {
?>


