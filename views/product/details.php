<?
$clientScript=Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide/highslide-with-gallery.js', CClientScript::POS_HEAD);
?>
<script type="text/javascript"> 
hs.graphicsDir = '/js/highslide/graphics/';
hs.align = 'center';
hs.transitions = ['expand', 'crossfade'];
hs.outlineType = 'rounded-white';
hs.fadeInOut = true;
hs.numberPosition = 'caption';
hs.dimmingOpacity = 0.75;
 
// Add the controlbar
if (hs.addSlideshow) hs.addSlideshow({
	//slideshowGroup: 'group1',
	interval: 5000,
	repeat: false,
	useControls: true,
	fixedControls: 'fit',
	overlayOptions: {
		opacity: .75,
		position: 'bottom center',
		hideOnMouseOut: true
	}
});
</script> 
 <?php
 if (trim($product->product_html_title)) $this->pageTitle=$product->product_html_title;
 else $this->pageTitle=$product->product_name;
 if(isset($product->product_html_keywords)) $this->pageKeywords = $product->product_html_keywords;
 if(isset($product->product_html_description)) $this->pageDescription = $product->product_html_description; 
  ?>

<div id="Right_column">
<?
$RC = new RightColumn(2,'L');
?>
</div>

<div id="mainContent">
<?
$path_text = $this->get_productiya_path($product->category_belongs);
if (@trim($path_text)) echo $path_text;
?>
<?
//print_r($models);
?>
<h2><?=$model->title?></h2>

<?
if (isset($additional_pictures) AND $additional_pictures!=NULL) {////////////Рисуем картинки
//SELECT picture_product.id, pictures.id, pictures.ext, pictures.type 
echo "<div class=\"highslide-gallery\">";
for ($i=0; $i<count($additional_pictures); $i++){
//print_r($additional_pictures[$i]);
$row = $additional_pictures[$i];
 $img_file = @file_exists ("pictures/add/$row[id].$row[ext]");
// echo "$row[id].$row[ext]";
if (@$img_file AND $row[ext]!='pdf') {
echo "<a target=\"_blank\"  id=\"thumb1\" class=\"highslide\" onclick=\"return hs.expand(this, { slideshowGroup: 1 } )\" href=\"http://".$_SERVER['HTTP_HOST']."/pictures/add/$row[id].$row[ext]\"  >";
?>
<img border="0"  align="middle" height="100" src="http://<?=$_SERVER['HTTP_HOST']?>/pictures/add/icons/<?=$row['id']?>.png" style="margin-bottom:5px; " title="<?=$product->product_name?>" alt="<?=$product->product_name?>">
<?
echo "</a>";
echo "<div class=\"highslide-caption\">".$product->product_name."</div>";
}
elseif(@$img_file AND $row[ext]=='pdf'){
?>
		<div style="float:left; width:50px; height:75px; margin-bottom:5px; margin-right:5px; text-align:center">
    <?
	if ($row[ext] == 'xls' OR $row[ext] == 'xlsx')  $pict = "<img height=\"75px\" width=\"60px\" src=\"/images/xls.png\">";
	else if ($row[ext] == 'pdf') $pict = "<img src=\"/images/pdf.png\" height=\"75px\" width=\"60px\">";
	else if ($row[ext] == 'doc' OR $row[ext] == 'docx') $pict = "<img height=\"75px\" width=\"60px\"  src=\"/images/doc.png\">";
	
	echo CHtml::link($pict, "/pictures/add/".$row[id].'.'.$row[ext], array('target'=>'_blank'));
	?>
    </div>
    <?
}///////////////////elseif(@$img_file AND $row[ext]=='pdf'){
 
 }
 echo "</div>";
 //echo "<br><br>";
}
?>
   <table width="100%" border="0" cellspacing="1" cellpadding="1" class="plain" bgcolor="#333333">
  <tr bgcolor="#C4CDCE">
    <td width="22" rowspan="2">&nbsp;</td> 
    <td rowspan="2" width="10%" >Артикул</td>
    <td rowspan="2" width="50%" >Номенклатура</td>
    <?
    if(Yii::app()->GP->GP_show_prices==1) {
	?>
    <td bgcolor="#FFFFFF">Цена</td>
    <?
    }/////////// if(Yii::app()->GP->GP_show_prices==1) {
	?>
    <td colspan="<?=count($stores_names)?>" bgcolor="#FFFFFF">Остатки</td>
  </tr>
  <tr bgcolor="#C4CDCE">
   <?
    if(Yii::app()->GP->GP_show_prices==1) {
	?>
    <td class="plainslim">Розн</td>
    <?
    }//////////////if(Yii::app()->GP->GP_show_prices==1) {
	?>
<?

for($k=0;$k<count($stores_names);$k++) {
$kk=$k+1;
?>
    <td class="plainslim" width="10%"><?=$stores_names[$k]?></td>
	<?
	}
	?>
  </tr>
<?


//while($next=mysql_fetch_object($res)) {
//for ($i=0; $i<count($models);$i++)
//print_r($models);
if (isset($models)) {
$m=0;
foreach($models as $n=>$next):
//$sum_prib = $sum_prib+$next->pribil;
//$sum_nds = $sum_nds+$next->nds_razn;
$article[]=$next['product_article'];
$sgr[]=$next['sgr'];
$gr[]=$next['gr'];
$price_with_nds[]=$next['price_with_nds'];
$price_with_nds2[]=$next['price_with_nds2'];
$price_card[]=$next['price_card'];
//echo $next['product_name'];
	$qqq = split(':::', $next['product_name']);
		 $product_name[]=$qqq[0];
 $pr_id[]=$next['id'];
if (@$enable_suplier==1)  $supname[]=$next['supname'];
if (@$enable_buyer==1) $kname[]=$next['kname'];
if (@$detailed) $doc_id1[]=$next['doc_id1'];
if (@$detailed) $operation_dt[]=$next['operation_dt'];

for($k=0;$k<count($stores_id);$k++) {
$kk=$k+1;
$prst='prihod_store'.$kk;
$rhst='rashod_store'.$kk;
$sum_num[$kk][]=$next[$prst] - $next[$rhst];
}//////////for($k=0;$k<count($stores_id);$k++) {

$m++;
//}//////////////while
endforeach;
}/////if (isset($models)) {
  for ($i=0; $i<$m; $i++) {
  $k=$i+1;
		  ?>
         
         <?
         echo CHtml::beginForm(); 
		 ?>
  <tr bgcolor="#FFFFFF">
    <td align="center" valign="middle" width="22"  class="plainslim" style="margin:0; margin-top:0; margin-bottom:0; margin-left:0; margin-right:0">
    <?
      echo CHtml::hiddenField('add_to_basket', $pr_id[$i] );
	  //(string $name, string $value='', array $htmlOptions=array ( ))
	?>
    <input  type="submit"  style="cursor:pointer" value="Купить" <?
?>></td>
    <td align="left" valign="middle"  class="plainslim"><?=$article[$i]?></td>

    <td valign="middle"  class="plainslim" width="100%"><?=@$product_name[$i]?></td>
<?
    if(Yii::app()->GP->GP_show_prices==1) {
	?>
        <td> 
      <?
      if (isset($price_with_nds[$i])) echo $price_with_nds[$i];
	  else echo $price_card[$i];
	  ?>    </td><?
     }////////// if(Yii::app()->GP->GP_show_prices==1) {
	?>
   
    <?
for($k=0;$k<count($stores_id);$k++) {
$kk=$k+1;
?>
    <td><?
	
	if (Yii::app()->GP->GP_ostatki_mode==1) {
		if ($sum_num[$kk][$i]>0) echo $sum_num[$kk][$i];
		else echo "Звоните";
	}
	else {
	if ($sum_num[$kk][$i]>0) echo "в наличии";
	else echo "временно отсутствует";
	} //////////else {
	
	?></td>

	<?
	}//////////////for($k=0;$k<count($stores_id);$k++) {
	?>
  </tr></form>
  <?
		}///////////for
		?>
</table>
  <br>
<div align="center">
   <?
    
	$file_name=$_SERVER['DOCUMENT_ROOT']."/pictures/img/".$_GET['pd'].".jpg";
	$file_link="http://".$_SERVER['HTTP_HOST']."/pictures/img/".$_GET['pd'].".jpg";
		if (file_exists($file_name)) echo "<img src=\"$file_link\" border=\"0\" style=\"max-width:640px\" alt=\"".$product->product_name."\" title=\"".$product->product_name."\">";
		else {
			$file_name=$_SERVER['DOCUMENT_ROOT']."/pictures/img_med/".$_GET['pd'].".jpg";
	$file_link="http://".$_SERVER['HTTP_HOST']."/pictures/img_med/".$_GET['pd'].".jpg";
	if (file_exists($file_name)) echo "<img src=\"$file_link\" border=\"0\" style=\"max-width:640px\" alt=\"".$product->product_name."\" title=\"".$product->product_name."\">";
		}
	?>
    </div><br>
    <div align="justify">
     <?
 //if (isset($product->product_full_descr)) echo $product->product_full_descr;
 ?>
    <br></div>

  
 

<?
//print_r($characterictics);
$tab = new CTabView;
	$tab->tabs=array(
    'tab1'=>array(
          'title'=>'Параметры',
          'view'=>'details_properties',
          'data'=>array('characterictics'=>$characterictics),
	 ),
	 
	
	
	
	);
	
	
if (@trim($product->product_full_descr)) {
$tab->tabs['tab2']=array(
          'title'=>'Описание',
          'content'=>$product->product_full_descr,
    );

}

if (count($compabile)>0) {
$tab->tabs['tab3']=array(
          'title'=>'Совместимые товары',
		  'view'=>'compabile',
          'data'=>array('compabile'=>$compabile),
    );

}
	
	
	
	$tab->run();
?>

</div>
