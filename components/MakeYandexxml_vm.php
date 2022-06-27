<?php

class MakeYandexxml_vm extends MakeXml
{

	

public function makefile() {
	
$allowed_directories="1926, 2483, 1278 ,2456 , 1531 , 1532 , 1090 ,  548 ,  351 ";	


$this->make_query(NULL, $allowed_directories);

	

$fp_vm = fopen($this->docroot.'/pictures/files/yandex_market_vm.xml', 'w'); //////////////Отдельный файл вендер - модел



//header("Content-type: text/xml");
fwrite($fp_vm,  "<?xml version=\"1.0\"  encoding=\"UTF-8\"?>\r\n");




if (isset($this->products) AND $this->groups) {
	
	ob_start();
	?>
<yml_catalog date="<?php echo date('Y-m-d H:i')?>">
<shop>
<name><?php echo $this->site?></name>
<company><?php echo $this->site?></company>
<url>http://<?php echo $this->site?></url>
<currencies>
<currency id="RUR" rate="1"/>
</currencies>
<categories>
	<?php
	$buf = ob_get_contents();
	ob_end_clean();
	fwrite($fp_vm,  $buf);
	$buf='';


	
	for ($k=0; $k<count($this->categories); $k++) {
		if($this->categories[$k]->category_id == $this->categories[$k]->parent) {
			echo 'Ошибка с группой '.$this->categories[$k]->category_id;
			exit();
		}
		if($this->categories[$k]->parent==Yii::app()->params['main_tree_root']) $buf= '<category id="'.$this->categories[$k]->category_id.'">'.htmlspecialchars($this->categories[$k]->category_name).'</category>';
		elseif(trim($this->categories[$k]->category_name)!='') $buf= '<category id="'.$this->categories[$k]->category_id.'" parentId="'.$this->categories[$k]->parent.'">'.htmlspecialchars($this->categories[$k]->category_name).'</category>';
		fwrite($fp_vm,  $buf);
	}
	ob_start();
	?>
</categories>	
<offers>
<?php
	$buf = ob_get_contents();
	ob_end_clean();
	fwrite($fp_vm,  $buf);
	$buf='';
	

$proceed	 = 0;
for ($i=0; $i<count($this->products); $i++) { 

if(isset($this->groups[$this->products[$i]->category_belong])) {



ob_start();
?>
  <?php
  $vender_model = 0;
  $add_params = '';
    if(isset(Yii::app()->params['market_params']))  {
			$params = Products::model()->get_product_params($this->products[$i]->id);
			if($params['vender_model']==true) {
				$vender_model = 1;
				$add_params = $params['attr'];
			}
	}
	?>
<offer id="<?php echo $this->products[$i]->id?>" <?php
if($vender_model==1) echo ' type="vendor.model" ';
?> available="true">

	<url><?php

    //$url='http://'.$site.urldecode(Yii::app()->createUrl('product/details' ,array('alias'=>$products[$i]->belong_category->alias, 'path'=>FHtml::urlpath($products[$i]->belong_category->path), 'pd'=>$products[$i]->id ) ) );
		  if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true) $url='http://'.$this->site.urldecode( '/catalog/'.FHtml::urlpath($this->products[$i]->belong_category->path).'/'.$this->products[$i]->belong_category->alias.'/'.$this->products[$i]->id.'.'.'html' ) ;
		  else  $url='http://'.$this->site.urldecode( '/product/'.$this->products[$i]->id.'.'.'html' ) ;
	echo $url;
	?></url>
    <price><?php echo $this->products[$i]->product_price?></price>
    <currencyId>RUR</currencyId>
    <categoryId><?php echo $this->products[$i]->category_belong?></categoryId>
    <?php
	if(isset($this->pictures_list[$this->products[$i]->id]))   $iconname = "/pictures/add/".$this->pictures_list[$this->products[$i]->id]['pict_id'].'.'.$this->pictures_list[$this->products[$i]->id]['ext'];
	if (file_exists($this->docroot.$iconname)==1 AND is_file($this->docroot.$iconname)) {
			?>
			<picture>
            http://<?php echo $this->site?><?php echo $iconname?>
        </picture>
			<?php
	} 
	?>
  <name><?php 
 echo htmlspecialchars($this->products[$i]->product_html_keywords); 
  ?></name>
  
<?php	
  if(trim($add_params)) echo $add_params;

  ?>
    <description> 
    <?php
    echo  htmlspecialchars($this->products[$i]->product_html_description);
	?>
    </description>

</offer>
<?php

$buf = ob_get_contents();
	ob_end_clean();
	if($vender_model==1) {
		$proceed++;
		fwrite($fp_vm,  $buf);
	}
	$buf='';
}////////if(isset($this->groups[$this->products[$i]->category_belong])) {
 }////////for ($i=0; $i<count($products); $i++) {
	 ob_start();
?>
</offers>
	</shop>
    </yml_catalog>
	<?php
}//////////


$buf = ob_get_contents();
	ob_end_clean();
	fwrite($fp_vm,  $buf);
	$buf='';

fclose($fp_vm); //Закрытие файла

				
	echo 'Selected from DB : '.count($this->products)." products \n";			
	echo 'Saved to pricelist vendor.model: '.$proceed." products \n";			
				

	

}/////////public function makefile() {
	
	
}



