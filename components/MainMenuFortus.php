<?php
class MainMenuFortus extends CWidget {

var $points;
var $current_cont;
var $cur_action;
function __construct($controller, $action){ 
		$this->current_cont = $controller;
		$this->cur_action = $action;
		$this->points = array(	
								//'Reghelp.ru'=>array('url'=>'http://www.reghelp.ru/', 'noindex'=>true),
								//'Главная'=>array('url'=>'/', 'controller'=>array('site'),'action'=>array('index') ),
								
							//	'Замки'=>array('url'=>'/catalog/auto/kpp', 'controller'=>array('constructcatalog'), 'noindex'=>false, 'childs'=>array(
							//'Замки'=>array('url'=>'/zamki/auto/kpp', 'controller'=>array('constructcatalog'), 'noindex'=>false, 'actions'=>array('index', 'zamki', 'group', 'info'), 'childs'=>array(
							'Продукция'=>array('url'=>'/catalog/auto/kpp', 'controller'=>array('constructcatalog'), 'noindex'=>false, 'actions'=>array('index', 'zamki', 'group', 'info'), 'childs'=>array(
										'Автомобильные'=>array(
												'locktype'=>'auto', 
												'url'=>'/catalog/auto/kpp',
											//'controller'=>array('constructcatalog'),
											//'url'=>'/constructcatalog/'
												 'childs'=>array(
												 	'Замки КПП'=>array('krepltype'=>'kpp'),
													'Замки рулевого вала'=>array('krepltype'=>'val'),
													'Замки капота'=>array('krepltype'=>'hood'),
													//'Защита запасного колеса'=>array('krepltype'=>'sparetire'),
													'Защита запасного колеса'=>array('krepltype'=>'sparetire'), 
													'Защита фар'=>array('krepltype'=>'headlamp'),
													'Электромеханические замки КПП'=>array('krepltype'=>'electromeh'),
													'Бесштыревые замки РВ'=>array('krepltype'=>'pinlessval'),
												 ),
										),
										/* 
										'Для мотоциклов'=>array(
											'locktype'=>'moto',
											'url'=>'/catalog/moto',
										),
										'Прочие замки'=>array(
											'locktype'=>'outher',
											//'url'=>'/catalog/outher/garage',
											'url'=>'/catalog/outher/headlamp',
											 'childs'=>array(
											 		
												 	'Контейнерные'=>array('krepltype'=>'garage'),
													'Для полуприцепов'=>array('krepltype'=>'pricep'),
												 ),
										)
										*/
									)
								),
					
				/*
								'О компании'=>array('url'=>'/page/about', 'controller'=>array('page', 'vacancy', 'site'), 'alias'=>array('about',  'listall', 'vacancyinfo', 'contacts'), 'actions'=>array('listall', 'vacancyinfo', 'cooperation'), 'noindex'=>false, 'childs'=>array( 
										'Этапы развития'=>array('controller'=>'page', 'alias'=>'about', 'url'=>'/page/about'),
										'Сотрудничество'=>array('controller'=>'site', 'url'=>'/site/cooperation', 'actions'=>array('cooperation')),
										'Вакансии'=>array('controller'=>'vacancy', 'url'=>'/vacancy/listall', 'actions'=>array('listall', 'vacancyinfo')), 
										'Контакты'=>array('controller'=>'page', 'url'=>'/page/contacts', 'alias'=>'contacts')
									)
								),
				*/
								
								//'Продукция'=>array('url'=>'/constructcatalog', 'controller'=>array('constructcatalog'), 'noindex'=>false),
							
							//	'Новости'=>array('url'=>'/news/list', 'controller'=>array('page'), 'action'=>array('list'), 'noindex'=>false),
							//	'Вопрос-Ответ'=>array('url'=>'/page/faq', 'controller'=>array('page'), 'action'=>array('faq'),  'noindex'=>false), 
								'Покупателям'=>array('url'=>'/news/list', 'controller'=>array('page'), 'alias'=>array('list', 'actions', 'inginiring', 'warranty', 'delivery', 'faqrubrics' , 'innovation', 'technology', 'fortusnews'),  'noindex'=>false, 'childs'=>array(
									//'Новости'=>array('controller'=>'news', 'alias'=>array('list'),  'actions'=>array('list', 'fortusnews'),  'url'=>'/news/list'),
									'Акции'=>array('controller'=>'page', 'url'=>'/page/actions', 'alias'=>array('actions')),
									/*'Инжиниринг'=>array('controller'=>'page', 'url'=>'/page/innovation', 'alias'=>array('inginiring', 'innovation', 'technology'), 'childs'=>array(
										'Инновации'=>array('controller'=>'page', 'url'=>'/page/innovation', 'alias'=>array('innovation')),
										'Технологии'=>array('controller'=>'page', 'url'=>'/page/technology', 'alias'=>array('technology')),
		
									)
									),*/
									'Гарантия'=>array('controller'=>'page', 'url'=>'/page/warranty', 'alias'=>array('warranty')),
									'Доставка'=>array('controller'=>'page', 'url'=>'/page/delivery', 'alias'=>array('delivery')),
									//'Вопросы и Ответы'=>array('controller'=>'page', 'url'=>'/page/faq', 'alias'=>array('faq'),  'actions'=>array('faqrubrics'),),
								)
								),
								
								'Где купить'=>array('url'=>'/site/map', 'controller'=>array('site', 'page'), 'alias'=>array('map', 'show', 'city', 'region'),  'noindex'=>false),
								
								'Аксессуары'=>array('url'=>'/catalog/tools', 'controller'=>array('constructcatalog') ,  'actions'=>array('tools', 'adv'),  'noindex'=>false,  'childs'=>array(
									//'Реклама'=>array('controller'=>'constructcatalog', 'url'=>'/catalog/adv', 'actions'=>array('adv')) ,
									'Инструмент'=>array('controller'=>'constructcatalog', 'url'=>'/catalog/tools', 'actions'=>array('tools')) 
								),
										
								),
								'Контакты'=>array('url'=>'/page/contacts', 'controller'=>array('page'),  'alias'=>array('contacts'), 'noindex'=>false),
								
								);
		
}

function draw(){
	
$this->render('constructmenu2',  array('controller'=>$this->current_cont, 'action'=>$this->cur_action));
}/////function draw(){
	
function draw_one(){
	
$this->render('fortus/topmenu/fortusmenu',  array('controller'=>$this->current_cont, 'action'=>$this->cur_action));
}/////function draw(){
	
}////class
?>