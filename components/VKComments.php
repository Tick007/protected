<?php
class VKComments   extends CWidget {
	var $menu_levels;
	private $show_group;
	public $models;
	var $headtext;

	function __construct($headtext=NULL){
		if($headtext!=NULL) $this->headtext = $headtext;
	}

	function Draw($view=NULL, $width=NULL) {

		$this->render($view, array('width'=>$width));
	}///////////////public function Draw() {



}///////////class Vitrina {
?>


