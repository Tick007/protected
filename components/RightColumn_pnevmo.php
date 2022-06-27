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
    <noindex>

<br>
<div align="center">
<!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<a href='http://www.liveinternet.ru/click' "+
"target=_blank><img src='//counter.yadro.ru/hit?t27.18;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet: показано количество просмотров и"+
" посетителей' "+
"border='0' width='88' height='120'><\/a>")
//--></script><!--/LiveInternet-->
<br>
<!-- Yandex.Metrika informer -->
<a href="http://metrika.yandex.ru/stat/?id=8145121&amp;from=informer"
target="_blank" rel="nofollow"><img src="//bs.yandex.ru/informer/8145121/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" /></a>
<!-- /Yandex.Metrika informer -->

<!-- Yandex.Metrika counter -->
<div style="display:none;"><script type="text/javascript">
(function(w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter8145121 = new Ya.Metrika({id:8145121, enableAll: true});
        }
        catch(e) { }
    });
})(window, "yandex_metrika_callbacks");
</script></div>
<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript" defer="defer"></script>
<noscript><div><img src="//mc.yandex.ru/watch/8145121" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</div></noindex>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-26920789-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>


	<?
	}

}
?>