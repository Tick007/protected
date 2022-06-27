<?php
/*
 $clientScript = Yii::app()->clientScript;
// $clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/diapo1.css', CClientScript::POS_HEAD);
  $clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/diapo.css', CClientScript::POS_HEAD);
?>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<!--[if !IE]><!--><script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.mobile-1.0rc2.customized.min.js"></script><!--<![endif]-->
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.easing.1.3.js"></script> 
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.hoverIntent.minified.js"></script> 
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/diapo.min.js"></script> 

<script>
$(function(){
	$('.pix_diapo').diapo();
});

</script>

<style>
section {
	display: block;
	overflow: hidden;
	position: relative;
}

</style>
 



<script>
$(function(){
	$('.pix_diapo').diapo();
});

</script>
 <section> 
    
    	<div style="overflow:hidden; width:920px; margin: 0px auto; padding:0 px; height:286px"> 
                <div class="pix_diapo">
					
                    <?php
					/*
                    for($i=0; $i<count($this->models); $i++) {
						 $iconname = Yii::app()->request->baseUrl . "/pictures/add/icons/" .$this->models[$i]->icon . '.png';
						    if ( file_exists( $_SERVER['DOCUMENT_ROOT'] . $iconname ) == 1 ) {
								$img = Yii::app()->request->baseUrl . "/pictures/add/" .$this->models[$i]->icon . '.'.$this->models[$i]->ext;
					?>
                    	<div data-thumb="<?php echo $iconname?>">
                        <img src="<?php echo $img?>" width="500px" height="250px">
                        <div class="caption elemHover fromLeft">
                          <?php
                          echo $this->models[$i]->product_name;
						  ?>
                        </div>
                    </div>
					<?php
							}
					}
					?>	*/
					/*
					?>
					<div data-thumb="/themes/enterteh/images/1362228545.jpg">
                        <img src="/themes/enterteh/images/1362228545.jpg" width="920" height="286" >
                        <div class="caption elemHover fromLeft">Не доставили вовремя? Значит бесплатно!
                        </div>
                    </div>
					<div data-thumb="/themes/enterteh/images/1362229112.jpg">
                        <img src="/themes/enterteh/images/1362229112.jpg" width="920" height="286">
                        <div class="caption elemHover fromLeft">Какой-то текст
                        </div>
                    </div>
				
                    
                   
               </div><!-- #pix_diapo -->
                
        </div>
    
    
    </section> 
<?php

*/
?>

<?php
function get_files($path, $order = 0, $mask = '*')
{
	
    $sdir = array();
        // получим все файлы из дирректории
        if (false !== ($files = scandir($path, $order)))
        {  
				foreach ($files as $i => $entry) 
                {
                       // если имя файла подходит под маску поика      
                       if ($entry != '.' && $entry != '..' && strstr($entry, $mask)) 
                       {
                              $sdir[] = $entry;
                       }
            }
        }
    return ($sdir);
}
?>

<?php


// $banners = get_files($_SERVER['DOCUMENT_ROOT'].'/themes/enterteh/images', 1, 'diapo_banner_');
//elseif(isset($krepltype) AND $krepltype=='kpp') $banners = get_files($_SERVER['DOCUMENT_ROOT'].'/themes/fortus/img/', 1, 'banner_kpp');
//elseif(isset($krepltype) AND $krepltype=='hood') $banners = get_files($_SERVER['DOCUMENT_ROOT'].'/themes/fortus/img/', 1, 'banner_kpp');
//print_r( $banners);
if(isset($this->models) AND empty($this->models)==false) {
	?>
	<div id="rotator"><ul>
	<?php
	//$ban_num = rand(0, count($banners)-1);
	//echo $banners[$ban_num];
					
                    for($i=0; $i<count($this->models); $i++) {
						 $iconname = Yii::app()->request->baseUrl . "/pictures/add/icons/" .$this->models[$i]->icon . '.png';
						    if ( file_exists( $_SERVER['DOCUMENT_ROOT'] . $iconname ) == 1 ) {
								$img = Yii::app()->request->baseUrl . "/pictures/add/" .$this->models[$i]->icon . '.'.$this->models[$i]->ext;
					?>
                    	<li style="opacity: 0;" class="">
                        <?php
                        $url=Yii::app()->createUrl('product/details',array('pd'=>$this->models[$i]->id, 'alias'=>$this->models[$i]->belong_category->alias));
						?>
                       <a href="<?php echo $url?>"> <img src="<?php echo $img?>"  width="<?php
        if(isset($imgwidth)) echo $imgwidth;
		else echo '920';
		?>"   height="<?php
        if(isset($imgheight)) echo $imgheight;
		else echo '350';
		?>"></a>
					</li>
					<?php
							}
					}
					?>	
      </ul></div>
	<?php
}

?>

<script>
function theRotator() {
	// Устанавливаем прозрачность всех картинок в 0
	$('div#rotator ul li').css({opacity: 0.0});
 
	// Берем первую картинку и показываем ее (по пути включаем полную видимость)
	$('div#rotator ul li:first').css({opacity: 0.0});
 rotate();
	// Вызываем функцию rotate для запуска слайдшоу, 5000 = смена картинок происходит раз в 5 секунд
	setInterval('rotate()',5000);
}
 
function rotate() {	
	// Берем первую картинку
	var current = ($('div#rotator ul li.show')?  $('div#rotator ul li.show') : $('div#rotator ul li:first'));
 
	// Берем следующую картинку, когда дойдем до последней начинаем с начала
	var next = ((current.next().length) ? ((current.next().hasClass('show')) ? $('div#rotator ul li:first') :current.next()) : $('div#rotator ul li:first'));	
 
	// Расскомментируйте, чтобы показвать картинки в случайном порядке
	// var sibs = current.siblings();
	// var rndNum = Math.floor(Math.random() * sibs.length );
	// var next = $( sibs[ rndNum ] );
 
	// Подключаем эффект растворения/затухания для показа картинок, css-класс show имеет больший z-index
	next.css({opacity: 0.0})
	.addClass('show')
	.animate({opacity: 1.0}, 1000);
 
	// Прячем текущую картинку
	current.animate({opacity: 0.0}, 1000)
	.removeClass('show');
};
 
$(document).ready(function() {		
	// Запускаем слайдшоу
	theRotator();
});
 
</script>