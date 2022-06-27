<?php
class MainMenuConstruct extends CWidget {

var $points;
var $current_cont;
var $cur_action;
function __construct($controller, $action){
		$this->current_cont = $controller;
		$this->cur_action = $action;
		$this->points = array(	
								//'Reghelp.ru'=>array('url'=>'http://www.reghelp.ru/', 'noindex'=>true),
								'Главная'=>array('url'=>'/', 'controller'=>array('site'),'action'=>array('index') ),
								//'Организации'=>array('url'=>'/merchant/', 'controller'=>array('merchant'), 'noindex'=>false),
								'О компании'=>array('url'=>'/page/about', 'controller'=>array('page'), 'alias'=>array('about'),  'noindex'=>false),
								//'Продукция'=>array('url'=>'/constructcatalog', 'controller'=>array('constructcatalog'), 'noindex'=>false),
								'Продукция'=>array('url'=>'/page/production', 'controller'=>array('page'), 'alias'=>array('production'),  'noindex'=>false),
								'Новости'=>array('url'=>'/news/list', 'controller'=>array('page'), 'action'=>array('list'), 'noindex'=>false),
								'Вопрос-Ответ'=>array('url'=>'/page/faq', 'controller'=>array('page'), 'action'=>array('faq'),  'noindex'=>false), 
								'Контакты'=>array('url'=>'/page/contacts', 'controller'=>array('page'), 'alias'=>array('contacts'),   'noindex'=>false),
								
								);
		
}

function draw(){
	
$this->render('constructmenu2',  array('controller'=>$this->current_cont, 'action'=>$this->cur_action));
}/////function draw(){
	
function draw_one(){
	
$this->render('constructmenu',  array('controller'=>$this->current_cont, 'action'=>$this->cur_action));
}/////function draw(){
	
}////class
?>