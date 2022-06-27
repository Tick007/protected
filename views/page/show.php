 <?php $this->pageTitle=$model->title ?>

<div id="Right_column" style="margin-left:0px;">
    <?
    $LC = new RightColumn(3, 'L');
	?>
</div>

<div id="mainContent" style="padding-left:3px;">
<h2><?=$model->title?></h2>
<?
$this_page_contents = trim($model->contents);
if (isset($this_page_contents)) {
//echo "<h1>$this_page_title</h1>";

preg_match_all('[\[exec\]((.|\n)*)\[\/exec\]]', $this_page_contents, $out);
		//print_r($out);
		//echo "<br>";

		for($i=0; $i<count($out) ; $i++) {
			if(isset($out[1][$i])){
			$str = $out[1][$i];
			$str = str_replace("\\'", "'", $str);
			ob_start();
			eval($str);
			$executed = ob_get_contents();
			ob_end_clean();

			$this_page_contents = str_replace($out[0][$i], $executed, $this_page_contents);
			}
		}////////////for($i=0; $icount($out) ; $i++) {
		//echo $model->fullText;

		
//echo $model->fullText;
echo $this_page_contents;
}
?>
</div>

