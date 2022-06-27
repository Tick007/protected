<?
class RightColumn extends CWidget {
	var $connection;
		var $command;
		var $dataReader;
		var $row;
		var $chapter;
		var $position;
		
	public function __construct($chapter, $position){
	$this->chapter = $chapter;
	$this->position = $position;
	
	
	
	?>
	<div align="center"><h3><a href="/product/sale">Распродажа !</a></h3></div>
<br>
	<?php
	
	$this->show_items();
	}
		
	public function show_items() {//////Вытаскиваем модули для правой колонки
	$this->connection = Yii::app()->db;
			//$chapter = 4;
			$query_inclusion="SELECT theme_files.file, theme_files.name  FROM theme_chapters_files  JOIN theme_files ON theme_chapters_files.file_id = theme_files.id 
			WHERE theme_chapters_files.theme_id = ".Yii::app()->GP->GP_theme."  AND theme_chapters_files.chapter_id = $this->chapter AND file_enabled = 1 AND location='".$this->position."' ORDER BY theme_chapters_files.sort ";
			//echo $query_inclusion;
			$this->command=$this->connection->createCommand($query_inclusion)	;
			$dataReader=$this->command->query();
			//$data = $this->dataReader->readAll();
			//$data[0] = NULL;
			//$i=0;
			while(($row=$dataReader->read())!==false) {
			//$i++;
			$file_to_incl = trim($row['file']);
			//$file_to_incl = basename($file_to_incl);
			//$file_to_incl=explode(".",$file_to_incl);
			//echo $i." ".$file_to_incl."<br>";
				$fname= $_SERVER['DOCUMENT_ROOT'].'/protected/components/'.$file_to_incl.'.php';
			//echo $fname;
			//exit();
			if (is_file($fname) AND file_exists($fname)) {
					//Yii::import('components\$file_to_incl');
					$SM = new $file_to_incl;
					
					
					/*echo "<div width=\"200px\"><div id=\"my_block_head_left\">&nbsp;</div>
					<div id=\"my_block_head_midle\">".$row['name']."</div>
					<div id=\"my_block_head_right\">&nbsp;</div>
					<div style=\"clear:both; width:200px\"></div>
					<div id=\"my_block\">";*/
					echo "<div  id=\"vertmenu_top\">".$row['name']."</div>";
					
					$SM->Draw();
					echo "<div id=\"vertmenu_bottom\">&nbsp;</div>";
					echo "<br>";
					//$fname=basename($file_to_incl);
					//$this->widget($file_to_incl);
					//Yii::app()->GP->GP_self_contragent
					//Yii::import('system.web.CController');
					//echo tablestyle2("", "", $mode="");
					//echo "<br>";
			}
			}
	//return "ewrwerwer";
	?><br>
 
    <div align="center"></div>
<br>

<script type="text/javascript" src="//vk.com/js/api/openapi.js?96"></script>

<!-- VK Widget -->
<div id="vk_groups"></div>
<script type="text/javascript">
VK.Widgets.Group("vk_groups", {mode: 0, width: "200", height: "290"}, 34274893);
</script><br>

<br>

<div align="center">
<!-- Facebook Badge START --><a href="https://www.facebook.com/protuning.psg" target="_TOP" title="Protuning  Psg"><img src="https://badge.facebook.com/badge/100007658733986.11024.1171405991.png" style="border: 0px;" /></a><br/><!-- Facebook Badge END -->
</div>

<!--https://www.facebook.com/protuning.psg-->
 <br>
<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FProtuning%2F229865740533458&amp;width=200&amp;height=290&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=false&amp;show_border=true&amp;appId=1447031632198123" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:290px;" allowTransparency="true"></iframe>
<br>
<div align="center">

<!--
 <img height="100" src="/themes/protuning/images/x_5806cc55.jpg"  border="0" title="С новым годом" alt="С новым годом"/><br><br>-->

       <!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<a href='http://www.liveinternet.ru/click' "+
"target=_blank><img src='//counter.yadro.ru/hit?t27.10;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";h"+escape(document.title.substring(0,80))+";"+Math.random()+
"' alt='' title='LiveInternet: показано количество просмотров и"+
" посетителей' "+
"border='0' width='88' height='120'><\/a>")
//--></script><!--/LiveInternet-->
<br>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36387935-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<br>
<!-- Yandex.Metrika informer -->
<a href="http://metrika.yandex.ru/stat/?id=18770716&amp;from=informer"
target="_blank" rel="nofollow"><img src="//bs.yandex.ru/informer/18770716/3_1_CDFFCDFF_ADDFADFF_0_pageviews"
style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:18770716,type:0,lang:'ru'});return false}catch(e){}"/></a>
<!-- /Yandex.Metrika informer -->
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter18770716 = new Ya.Metrika({id:18770716,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/18770716" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
 

</div>
	<?
	}

}
?>